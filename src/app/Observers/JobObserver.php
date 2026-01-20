<?php

namespace App\Observers;

use App\Models\Job;
use Illuminate\Support\Facades\Cache;

class JobObserver
{
    /**
     * Handle the Job "saved" event.
     */
    public function saved(Job $job): void
    {
        // 都道府県リストのキャッシュをクリア
        Cache::forget('job_prefectures');
    }

    /**
     * Handle the Job "deleted" event.
     */
    public function deleted(Job $job): void
    {
        // 都道府県リストのキャッシュをクリア
        Cache::forget('job_prefectures');
    }
}

