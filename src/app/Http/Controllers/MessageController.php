<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Message;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
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
    public function store(Request $request, Job $job): RedirectResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = Auth::user();

        Message::create([
            'job_id' => $job->id,
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        return redirect()->route('messages.show', [
            'job' => $job,
            'with' => $request->receiver_id,
        ])->with('success', 'メッセージを送信しました');
    }
}
