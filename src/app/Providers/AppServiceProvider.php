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
        // 本番環境では HTTPS を強制（リバースプロキシ経由対応）
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Observerを登録
        ModelProfile::observe(ModelProfileObserver::class);
        Job::observe(JobObserver::class);
    }
}
