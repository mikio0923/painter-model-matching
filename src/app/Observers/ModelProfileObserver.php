<?php

namespace App\Observers;

use App\Models\ModelProfile;
use Illuminate\Support\Facades\Cache;

class ModelProfileObserver
{
    /**
     * Handle the ModelProfile "saved" event.
     */
    public function saved(ModelProfile $modelProfile): void
    {
        // 都道府県リストとタグリストのキャッシュをクリア
        Cache::forget('model_prefectures');
        Cache::forget('model_tags');
    }

    /**
     * Handle the ModelProfile "deleted" event.
     */
    public function deleted(ModelProfile $modelProfile): void
    {
        // 都道府県リストとタグリストのキャッシュをクリア
        Cache::forget('model_prefectures');
        Cache::forget('model_tags');
    }
}

