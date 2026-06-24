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
                // 採用通知 → 該当依頼の詳細ページへ（モデルが内容を確認できるように）
                if ($notification->related_type === \App\Models\JobApplication::class) {
                    $application = \App\Models\JobApplication::find($notification->related_id);
                    if ($application?->job) {
                        return redirect()->route('jobs.show', $application->job);
                    }
                }
                return redirect()->route('model.applications.index');
            case 'application_rejected':
                // 辞退通知 → 応募一覧（履歴）
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
            case 'offer_received':
                // モデル向け: 個別依頼の詳細画面へ
                if ($notification->related_type === \App\Models\JobOffer::class) {
                    return redirect()->route('model.job-offers.show', $notification->related_id);
                }
                break;
            case 'offer_accepted':
            case 'offer_declined':
                // 画家向け: 送ったオファー一覧へ
                return redirect()->route('painter.job-offers.index');
            case 'favorite_received_model':
                // モデル向け: 自分のお気に入りされた人を確認できるページがまだ無いので
                // 一旦お気に入り通知元の Fav 一覧 (favorites.index) ではなくマイページに
                return redirect()->route('mypage');
            case 'favorite_received_job':
                // 画家向け: 該当の依頼詳細へ
                if ($notification->related_type === \App\Models\Job::class) {
                    $job = \App\Models\Job::find($notification->related_id);
                    if ($job) {
                        return redirect()->route('jobs.show', $job);
                    }
                }
                break;
        }

        // デフォルトは通知一覧に戻る
        return redirect()->route('notifications.index');
    }
}
