<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\Review;

class NotificationHelper
{
    /**
     * 応募通知を作成（画家向け）
     */
    public static function createApplicationReceivedNotification(JobApplication $application): void
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
    }

    /**
     * 応募承認通知を作成（モデル向け）
     */
    public static function createApplicationAcceptedNotification(JobApplication $application): void
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
    }

    /**
     * 応募却下通知を作成（モデル向け）
     */
    public static function createApplicationRejectedNotification(JobApplication $application): void
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
    }

    /**
     * メッセージ通知を作成
     */
    public static function createMessageReceivedNotification(Message $message): void
    {
        $job = $message->job;
        $sender = $message->sender;
        
        Notification::create([
            'user_id' => $message->receiver_id,
            'type' => 'message_received',
            'title' => '新しいメッセージが届きました',
            'body' => "{$sender->name}さんから「{$job->title}」に関するメッセージが届きました。",
            'related_id' => $message->id,
            'related_type' => Message::class,
        ]);
    }

    /**
     * レビュー通知を作成
     */
    public static function createReviewReceivedNotification(Review $review): void
    {
        $reviewedUser = $review->reviewedUser;
        $reviewer = $review->reviewer;
        $job = $review->job;
        
        Notification::create([
            'user_id' => $reviewedUser->id,
            'type' => 'review_received',
            'title' => '新しいレビューが投稿されました',
            'body' => "{$reviewer->name}さんから「{$job->title}」に関するレビューが投稿されました。",
            'related_id' => $review->id,
            'related_type' => Review::class,
        ]);
    }
}

