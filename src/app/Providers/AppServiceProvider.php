<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\ModelProfile;
use App\Models\Job;
use App\Observers\ModelProfileObserver;
use App\Observers\JobObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // APP_URL が https:// で始まる場合のみ HTTPS を強制
        // （SSL 設定前の HTTP 期間でも CSS/JS が壊れないように）
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Observerを登録
        ModelProfile::observe(ModelProfileObserver::class);
        Job::observe(JobObserver::class);
    }
}
