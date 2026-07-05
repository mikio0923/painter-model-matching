<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobOffer;
use App\Models\Message;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * 会話の当事者チェック。
     * この依頼についてメッセージを交わせるのは
     *   画家（依頼主） ⇄ その依頼に応募 or 個別依頼(オファー)されたモデル
     * のペアのみ。どちらでもなければ 403。
     */
    private function assertConversationParticipants(Job $job, int $userId, int $otherUserId): void
    {
        $painterId = (int) $job->painter_id;

        $isRelatedModel = function (int $modelUserId) use ($job): bool {
            return JobApplication::where('job_id', $job->id)->where('model_id', $modelUserId)->exists()
                || JobOffer::where('job_id', $job->id)->where('model_id', $modelUserId)->exists();
        };

        // 画家本人 → 相手はこの依頼に関係するモデルであること
        if ($userId === $painterId) {
            abort_unless($isRelatedModel($otherUserId), 403, 'この依頼に関係のない相手にはメッセージできません。');
            return;
        }

        // 関係モデル本人 → 相手は依頼主の画家であること
        if ($isRelatedModel($userId)) {
            abort_unless($otherUserId === $painterId, 403, 'この依頼の依頼主以外にはメッセージできません。');
            return;
        }

        abort(403, 'この依頼のメッセージに参加する権限がありません。');
    }

    /**
     * メッセージスレッド一覧を表示
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // 自分が送信または受信したメッセージから、スレッド（job_id + 相手）を取得
        $threads = Message::where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['job', 'sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($user) {
                // スレッドのキー: job_id + 相手のID
                $otherUserId = $message->sender_id === $user->id 
                    ? $message->receiver_id 
                    : $message->sender_id;
                return $message->job_id . '_' . $otherUserId;
            })
            ->map(function($messages) use ($user) {
                $firstMessage = $messages->first();
                $otherUser = $firstMessage->sender_id === $user->id
                    ? $firstMessage->receiver
                    : $firstMessage->sender;

                return (object)[
                    'job' => $firstMessage->job,
                    'other_user' => $otherUser,
                    'last_message' => $messages->first(),
                    'unread_count' => $messages->where('receiver_id', $user->id)
                        ->whereNull('read_at')
                        ->count(),
                ];
            })
            // 依頼や相手ユーザーが削除されたスレッドは URL 生成に必要な情報が欠けるため除外
            ->filter(fn($thread) => $thread->job !== null && $thread->other_user !== null)
            ->values();

        return view('messages.index', [
            'threads' => $threads,
        ]);
    }

    /**
     * メッセージスレッド詳細を表示
     */
    public function show(Request $request, Job $job): View
    {
        $user = Auth::user();
        
        // 相手のユーザーIDを取得（クエリパラメータから）
        $otherUserId = $request->get('with');
        
        if (!$otherUserId) {
            // 応募が承認されている場合、相手を自動判定
            $application = JobApplication::where('job_id', $job->id)
                ->where('status', 'accepted')
                ->where(function($query) use ($user) {
                    if ($user->role === 'model') {
                        $query->where('model_id', $user->id);
                    } else {
                        // 画家の場合、承認された応募者のモデルIDを取得
                        $query->whereHas('model');
                    }
                })
                ->first();
            
            if ($application) {
                $otherUserId = $user->role === 'model' ? $job->painter_id : $application->model_id;
            } else {
                abort(404);
            }
        }

        $otherUser = \App\Models\User::findOrFail($otherUserId);

        // 当事者（画家 ⇄ 応募/オファーされたモデル）以外は閲覧不可
        $this->assertConversationParticipants($job, (int) $user->id, (int) $otherUserId);

        // メッセージを取得
        $messages = Message::where('job_id', $job->id)
            ->where(function($query) use ($user, $otherUserId) {
                $query->where(function($q) use ($user, $otherUserId) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $otherUserId);
                })->orWhere(function($q) use ($user, $otherUserId) {
                    $q->where('sender_id', $otherUserId)
                      ->where('receiver_id', $user->id);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 未読メッセージを既読にする
        Message::where('job_id', $job->id)
            ->where('receiver_id', $user->id)
            ->where('sender_id', $otherUserId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', [
            'job' => $job,
            'otherUser' => $otherUser,
            'messages' => $messages,
        ]);
    }

    /**
     * メッセージを送信
     */
    public function store(Request $request, Job $job): RedirectResponse|JsonResponse
    {
        $request->validate([
            'body'        => ['nullable', 'string', 'max:5000'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        // 本文か画像のどちらかは必須
        if (!$request->filled('body') && !$request->hasFile('image')) {
            return $this->respondError(
                $request,
                'body',
                'メッセージ本文または画像のどちらかを入力してください。'
            );
        }

        $user = Auth::user();

        // 当事者（画家 ⇄ 応募/オファーされたモデル）以外は送信不可
        $this->assertConversationParticipants($job, (int) $user->id, (int) $request->receiver_id);

        // messages.image_path カラムが存在するかでファイル添付対応かを判定
        $hasImageColumn = \Illuminate\Support\Facades\Schema::hasColumn('messages', 'image_path');

        $imagePath = null;
        if ($hasImageColumn && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('messages', 'public');
        } elseif (!$hasImageColumn && $request->hasFile('image')) {
            // カラム未マイグレートの環境では画像添付を拒否（テキスト送信は通す）
            return $this->respondError(
                $request,
                'image',
                'サーバ側で画像添付の準備が完了していません。本文のみで送信してください。'
            );
        }

        $data = [
            'job_id'      => $job->id,
            'sender_id'   => $user->id,
            'receiver_id' => $request->receiver_id,
            'body'        => $request->input('body', ''),
        ];
        if ($hasImageColumn) {
            $data['image_path'] = $imagePath;
        }

        $message = Message::create($data);

        // 通知を作成（受信者に通知）
        NotificationService::notifyMessageReceived($message);

        // 非同期 (LINE 風) では JSON で返却し、ページ遷移せず DOM に挿入する
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $this->serializeMessage($message->fresh(['sender']), $user->id),
            ]);
        }

        return redirect()->route('messages.show', [
            'job' => $job,
            'with' => $request->receiver_id,
        ])->with('success', 'メッセージを送信しました');
    }

    /**
     * 新着メッセージのポーリング用 API（JSON）
     * GET /messages/job/{job}/poll?with={otherUserId}&since={lastMessageId}
     */
    public function poll(Request $request, Job $job): JsonResponse
    {
        $request->validate([
            'with'  => ['required', 'integer', 'exists:users,id'],
            'since' => ['nullable', 'integer', 'min:0'],
        ]);

        $user        = Auth::user();
        $otherUserId = (int) $request->get('with');
        $since       = (int) $request->get('since', 0);

        // 当事者以外はポーリング不可
        $this->assertConversationParticipants($job, (int) $user->id, $otherUserId);

        $query = Message::where('job_id', $job->id)
            ->where(function ($q) use ($user, $otherUserId) {
                $q->where(function ($qq) use ($user, $otherUserId) {
                    $qq->where('sender_id', $user->id)
                       ->where('receiver_id', $otherUserId);
                })->orWhere(function ($qq) use ($user, $otherUserId) {
                    $qq->where('sender_id', $otherUserId)
                       ->where('receiver_id', $user->id);
                });
            })
            ->where('id', '>', $since)
            ->orderBy('id', 'asc')
            ->with(['sender'])
            ->limit(200)
            ->get();

        // 自分宛の未読を既読化（ポーリングのたびに反映）
        Message::where('job_id', $job->id)
            ->where('receiver_id', $user->id)
            ->where('sender_id', $otherUserId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $payload = $query->map(fn($m) => $this->serializeMessage($m, $user->id))->all();

        return response()->json([
            'messages' => $payload,
            'last_id'  => $query->last()?->id ?? $since,
        ]);
    }

    /**
     * Message を非同期用の最小データに整形（HTML エスケープは JS 側で textContent 経由）
     */
    private function serializeMessage(Message $message, int $viewerId): array
    {
        return [
            'id'          => $message->id,
            'body'        => $message->body,
            'image_url'   => $message->image_url,
            'is_me'       => $message->sender_id === $viewerId,
            'sender_name' => $message->sender->name ?? '退会済みユーザー',
            'created_at'  => $message->created_at->format('m/d H:i'),
        ];
    }

    /**
     * バリデーション以外で送信を弾く時の共通レスポンス
     * （AJAX なら 422 + JSON、通常リクエストならフォームへ戻す）
     */
    private function respondError(Request $request, string $field, string $message): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'errors'  => [$field => [$message]],
            ], 422);
        }
        return back()->withErrors([$field => $message])->withInput();
    }
}
