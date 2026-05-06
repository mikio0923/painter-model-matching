<?php

namespace App\Services;

use App\Models\EmailPreference;
use App\Services\MessageNotificationThrottler;
use App\Models\Notification;
use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use App\Mail\ApplicationReceivedMail;
use App\Mail\ApplicationAcceptedMail;
use App\Mail\ApplicationRejectedMail;
use App\Mail\MessageReceivedMail;
use App\Mail\ReviewReceivedMail;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * 応募通知を作成（画家に通知）
     */
    public static function notifyApplicationReceived(JobApplication $application): void
    {
        $job = $application->job;
        $model = $application->model;

        Notification::create([
            'user_id' => $job->painter_id,
            'type' => 'application_received',
            'title' => '新しい応募が届きました',
            'body' => "「{$job->title}」に{$model->name}さんから応募がありました。",
            'related_id' => $application->id,
            'related_type' => JobApplication::class,
        ]);

        // 画家にメール通知（オプトアウト対応）
        $painter = User::find($job->painter_id);
        if ($painter && EmailPreference::allows($painter, 'application')) {
            Mail::to($painter->email)->queue(new ApplicationReceivedMail($application));
        }
    }

    /**
     * 応募承認通知を作成（モデルに通知）
     */
    public static function notifyApplicationAccepted(JobApplication $application): void
    {
        $job = $application->job;

        Notification::create([
            'user_id' => $application->model_id,
            'type' => 'application_accepted',
            'title' => '応募が承認されました',
            'body' => "「{$job->title}」への応募が承認されました。",
            'related_id' => $application->id,
            'related_type' => JobApplication::class,
        ]);

        // モデルにメール通知（オプトアウト対応）
        $model = User::find($application->model_id);
        if ($model && EmailPreference::allows($model, 'application')) {
            Mail::to($model->email)->queue(new ApplicationAcceptedMail($application));
        }
    }

    /**
     * 応募却下通知を作成（モデルに通知）
     */
    public static function notifyApplicationRejected(JobApplication $application): void
    {
        $job = $application->job;

        Notification::create([
            'user_id' => $application->model_id,
            'type' => 'application_rejected',
            'title' => '応募が却下されました',
            'body' => "「{$job->title}」への応募が却下されました。",
            'related_id' => $application->id,
            'related_type' => JobApplication::class,
        ]);

        // モデルにメール通知（オプトアウト対応）
        $model = User::find($application->model_id);
        if ($model && EmailPreference::allows($model, 'application')) {
            Mail::to($model->email)->queue(new ApplicationRejectedMail($application));
        }
    }

    /**
     * メッセージ通知を作成（受信者に通知）
     */
    public static function notifyMessageReceived(Message $message): void
    {
        $job = $message->job;
        $sender = $message->sender;

        Notification::create([
            'user_id' => $message->receiver_id,
            'type' => 'message_received',
            'title' => '新しいメッセージが届きました',
            'body' => "「{$job->title}」について{$sender->name}さんからメッセージが届きました。",
            'related_id' => $message->id,
            'related_type' => Message::class,
        ]);

        // 受信者にメール通知（オプトアウト + 頻度制御）
        $receiver = User::find($message->receiver_id);
        if ($receiver
            && EmailPreference::allows($receiver, 'message')
            && MessageNotificationThrottler::shouldSendMail($message, $receiver)) {
            Mail::to($receiver->email)->queue(new MessageReceivedMail($message));
        }
    }

    /**
     * レビュー通知を作成（レビューされたユーザーに通知）
     */
    public static function notifyReviewReceived(\App\Models\Review $review): void
    {
        $reviewer = $review->reviewer;
        $job = $review->job;

        Notification::create([
            'user_id' => $review->reviewed_user_id,
            'type' => 'review_received',
            'title' => '新しいレビューが届きました',
            'body' => "「{$job->title}」について{$reviewer->name}さんからレビューが届きました。",
            'related_id' => $review->id,
            'related_type' => \App\Models\Review::class,
        ]);

        // レビュー対象者にメール通知（オプトアウト対応）
        $reviewedUser = User::find($review->reviewed_user_id);
        if ($reviewedUser && EmailPreference::allows($reviewedUser, 'review')) {
            Mail::to($reviewedUser->email)->queue(new ReviewReceivedMail($review));
        }
    }
}
