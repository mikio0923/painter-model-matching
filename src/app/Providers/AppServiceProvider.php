<?php

namespace App\Providers;

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
        // Observerを登録
        ModelProfile::observe(ModelProfileObserver::class);
        Job::observe(JobObserver::class);
    }
}
