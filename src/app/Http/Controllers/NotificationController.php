<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * 通知一覧を表示
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * 通知を既読にする
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        // 自分の通知かチェック
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // 既読にする
        if ($notification->isUnread()) {
            $notification->markAsRead();
        }

        // 関連するページにリダイレクト
        return $this->redirectToRelated($notification);
    }

    /**
     * すべての通知を既読にする
     */
    public function markAllAsRead(): RedirectResponse
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->route('notifications.index')
            ->with('success', 'すべての通知を既読にしました');
    }

    /**
     * 通知の種類に応じて関連ページにリダイレクト
     */
    private function redirectToRelated(Notification $notification): RedirectResponse
    {
        switch ($notification->type) {
            case 'application_received':
                // 応募一覧ページへ
                if ($notification->related_type === \App\Models\JobApplication::class) {
                    $application = \App\Models\JobApplication::find($notification->related_id);
                    if ($application) {
                        return redirect()->route('painter.jobs.applications.index', $application->job);
                    }
                }
                break;
            case 'application_accepted':
            case 'application_rejected':
                // 応募一覧ページへ
                return redirect()->route('model.applications.index');
            case 'message_received':
                // メッセージ詳細ページへ
                if ($notification->related_type === \App\Models\Message::class) {
                    $message = \App\Models\Message::find($notification->related_id);
                    if ($message) {
                        $otherUserId = $message->sender_id === Auth::id() 
                            ? $message->receiver_id 
                            : $message->sender_id;
                        return redirect()->route('messages.show', [
                            'job' => $message->job,
                            'with' => $otherUserId,
                        ]);
                    }
                }
                break;
            case 'review_received':
                // レビュー詳細ページへ（またはジョブ詳細ページへ）
                if ($notification->related_type === \App\Models\Review::class) {
                    $review = \App\Models\Review::find($notification->related_id);
                    if ($review && $review->job) {
                        return redirect()->route('jobs.show', $review->job);
                    }
                }
                break;
        }

        // デフォルトは通知一覧に戻る
        return redirect()->route('notifications.index');
    }
}
