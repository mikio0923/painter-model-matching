<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * メッセージ受信メールの頻度制御
 *
 * 要件:
 *   - 同一スレッド: 5分以内に1通以上送らない（連続メッセージのバッファリング）
 *   - 1ユーザーあたり: 1時間に最大3通
 *   - オンライン判定: 直近5分以内にアクセスのあるユーザーには送らない
 */
class MessageNotificationThrottler
{
    private const THREAD_BUFFER_MINUTES = 5;
    private const MAX_PER_HOUR          = 3;
    private const ONLINE_WINDOW_MINUTES = 5;

    /**
     * このメッセージのメール送信を許可するか判定（許可ならカウンターを増やす）
     */
    public static function shouldSendMail(Message $message, User $receiver): bool
    {
        // オンライン判定（直近5分以内にアクセスあり）
        if (self::isReceiverRecentlyActive($receiver)) {
            return false;
        }

        // 同一スレッドのバッファ
        $threadKey = "msg-mail:thread:{$message->job_id}:{$receiver->id}";
        if (Cache::has($threadKey)) {
            return false;
        }

        // 受信者ごとの1時間あたり上限
        $rateKey = "msg-mail:rate:{$receiver->id}";
        $count = (int) Cache::get($rateKey, 0);
        if ($count >= self::MAX_PER_HOUR) {
            return false;
        }

        // 上限内なのでカウントとバッファをセット
        Cache::put($threadKey, true, now()->addMinutes(self::THREAD_BUFFER_MINUTES));
        Cache::put($rateKey, $count + 1, now()->addHour());

        return true;
    }

    /**
     * オンライン状態を更新する（毎リクエストでミドルウェア等から呼ぶ想定）
     */
    public static function markActive(?User $user): void
    {
        if (!$user) {
            return;
        }
        Cache::put("user:active:{$user->id}", true, now()->addMinutes(self::ONLINE_WINDOW_MINUTES));
    }

    private static function isReceiverRecentlyActive(User $receiver): bool
    {
        return Cache::has("user:active:{$receiver->id}");
    }
}
