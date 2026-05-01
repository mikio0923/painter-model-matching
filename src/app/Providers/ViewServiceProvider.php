<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\IdentityVerification;
use App\Models\Contact;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

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

        // 管理画面ナビに対応待ち件数を渡す
        View::composer('admin.layouts.app', function ($view) {
            $view->with('adminNavBadges', [
                'identity' => IdentityVerification::whereIn('status', ['pending', 'reviewing'])->count(),
                'contacts' => Contact::where(function ($q) {
                    $q->where('is_read', false)->orWhereNull('is_read');
                })->count(),
            ]);
        });
    }
}
