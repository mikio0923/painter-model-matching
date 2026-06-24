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
        $painter = $job->painter;
        $painterName = $painter?->painterProfile?->display_name ?? $painter?->name ?? '画家';

        $body  = "おめでとうございます。{$painterName}さんからの依頼「{$job->title}」にあなたが採用されました。\n\n";
        $body .= "下記より依頼内容の詳細をご確認のうえ、画家とのメッセージで日程や持ち物などをすり合わせてください。";

        Notification::create([
            'user_id'      => $application->model_id,
            'type'         => 'application_accepted',
            'title'        => '【採用通知】応募が採用されました',
            'body'         => $body,
            'related_id'   => $application->id,
            'related_type' => JobApplication::class,
        ]);

        // モデルにメール通知（オプトアウト対応）
        $model = User::find($application->model_id);
        if ($model && EmailPreference::allows($model, 'application')) {
            Mail::to($model->email)->queue(new ApplicationAcceptedMail($application));
        }
    }

    /**
     * 応募辞退通知を作成（モデルに通知）— 企業面接の不採用通知のような丁寧な定型文
     */
    public static function notifyApplicationRejected(JobApplication $application): void
    {
        $job = $application->job;
        $painter = $job->painter;
        $painterName = $painter?->painterProfile?->display_name ?? $painter?->name ?? '画家';

        $body  = "この度は{$painterName}さんの依頼「{$job->title}」へご応募いただき、誠にありがとうございました。\n\n";
        $body .= "慎重に検討いたしましたが、今回は他の方とのご縁とさせていただくこととなりました。";
        $body .= "あなたのプロフィールや作品は確かに拝見いたしましたので、ご縁がございましたら今後改めてお声がけさせていただく場合がございます。\n\n";
        $body .= "また別の依頼でお会いできることを楽しみにしております。";

        Notification::create([
            'user_id'      => $application->model_id,
            'type'         => 'application_rejected',
            'title'        => 'ご応募ありがとうございました',
            'body'         => $body,
            'related_id'   => $application->id,
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

    /**
     * 個別依頼が届いた（モデル宛）
     */
    public static function notifyOfferReceived(\App\Models\JobOffer $offer): void
    {
        $job     = $offer->job;
        $painter = $job->painter;
        $painterName = $painter->painterProfile?->display_name ?? $painter->name;

        Notification::create([
            'user_id'      => $offer->model_id,
            'type'         => 'offer_received',
            'title'        => '個別の仕事依頼が届きました',
            'body'         => "{$painterName}さんから「{$job->title}」の個別依頼が届いています。",
            'related_id'   => $offer->id,
            'related_type' => \App\Models\JobOffer::class,
        ]);
    }

    /**
     * 個別依頼が受諾された（画家宛）
     */
    public static function notifyOfferAccepted(\App\Models\JobOffer $offer): void
    {
        $job   = $offer->job;
        $model = $offer->model;
        $modelName = $model->modelProfile?->display_name ?? $model->name;

        Notification::create([
            'user_id'      => $job->painter_id,
            'type'         => 'offer_accepted',
            'title'        => '個別依頼が受諾されました',
            'body'         => "{$modelName}さんが「{$job->title}」の個別依頼を受諾しました。",
            'related_id'   => $offer->id,
            'related_type' => \App\Models\JobOffer::class,
        ]);
    }

    /**
     * モデルプロフィールが画家からお気に入りされた（モデル宛）
     */
    public static function notifyModelFavorited(\App\Models\ModelProfile $modelProfile, User $favoriter): void
    {
        $favoriterName = $favoriter->painterProfile?->display_name ?? $favoriter->name;

        Notification::create([
            'user_id'      => $modelProfile->user_id,
            'type'         => 'favorite_received_model',
            'title'        => 'お気に入りに追加されました',
            'body'         => "{$favoriterName}さんがあなたのプロフィールをお気に入りに追加しました。",
            'related_id'   => $modelProfile->id,
            'related_type' => \App\Models\ModelProfile::class,
        ]);
    }

    /**
     * 依頼がモデルからお気に入りされた（画家宛）
     */
    public static function notifyJobFavorited(Job $job, User $favoriter): void
    {
        $favoriterName = $favoriter->modelProfile?->display_name ?? $favoriter->name;

        Notification::create([
            'user_id'      => $job->painter_id,
            'type'         => 'favorite_received_job',
            'title'        => 'あなたの依頼がお気に入りに追加されました',
            'body'         => "{$favoriterName}さんが「{$job->title}」をお気に入りに追加しました。",
            'related_id'   => $job->id,
            'related_type' => Job::class,
        ]);
    }

    /**
     * 個別依頼が辞退された（画家宛）
     * 本文は丁寧な定型文。モデルからの補足コメントがある場合のみ末尾に添える。
     */
    public static function notifyOfferDeclined(\App\Models\JobOffer $offer): void
    {
        $job   = $offer->job;
        $model = $offer->model;
        $modelName = $model->modelProfile?->display_name ?? $model->name;

        $body  = "{$modelName}さんより、「{$job->title}」の個別依頼について、誠に恐縮ながら今回はお引き受けが難しいとのご連絡をいただきました。";
        $body .= "ご縁がありましたら、改めてどうぞよろしくお願いいたします。";
        if (!empty($offer->model_response)) {
            $body .= "\n\n― モデルさんからの補足 ―\n" . $offer->model_response;
        }

        Notification::create([
            'user_id'      => $job->painter_id,
            'type'         => 'offer_declined',
            'title'        => '個別依頼へのご返答が届きました',
            'body'         => $body,
            'related_id'   => $offer->id,
            'related_type' => \App\Models\JobOffer::class,
        ]);
    }
}
