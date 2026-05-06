<?php

namespace App\Http\Middleware;

use App\Services\MessageNotificationThrottler;
use Closure;
use Illuminate\Http\Request;

/**
 * 認証済みユーザーのアクティビティを記録する。
 * MessageNotificationThrottler の「直近5分以内オンライン」判定で使用。
 */
class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            MessageNotificationThrottler::markActive($user);
        }

        return $next($request);
    }
}
