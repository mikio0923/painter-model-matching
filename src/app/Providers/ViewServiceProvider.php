<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ナビゲーションバーに未読通知数を渡す
        View::composer('layouts.navigation', function ($view) {
            $unreadNotificationsCount = 0;
            if (Auth::check()) {
                $unreadNotificationsCount = Notification::where('user_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();
            }
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        });
    }
}
