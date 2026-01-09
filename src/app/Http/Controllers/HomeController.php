<?php

namespace App\Http\Controllers;

use App\Models\ModelProfile;
use App\Models\Job;

class HomeController extends Controller
{
    public function index()
    {
        // Pickup Model（ランダムに24件、画像があるものを優先）
        $pickupModels = ModelProfile::where('is_public', true)
            ->whereNotNull('profile_image_path')
            ->inRandomOrder()
            ->take(24)
            ->get();

        // 画像がない場合は画像なしも含める
        if ($pickupModels->count() < 24) {
            $additionalModels = ModelProfile::where('is_public', true)
                ->whereNull('profile_image_path')
                ->inRandomOrder()
                ->take(24 - $pickupModels->count())
                ->get();
            $pickupModels = $pickupModels->merge($additionalModels);
        }

        // Pickup Job（ランダムに5件）
        $pickupJobs = Job::where('status', 'open')
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Image Update Profile（最近画像を更新したモデル、画像があるもの）
        $imageUpdateModels = ModelProfile::where('is_public', true)
            ->whereNotNull('profile_image_path')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // 新着モデル（公開のみ）
        $models = ModelProfile::where('is_public', true)
            ->latest()
            ->take(6)
            ->get();

        // 新着依頼
        $jobs = Job::where('status', 'open')
            ->latest()
            ->take(6)
            ->get();

        // 新着レビュー
        $latestReviews = \App\Models\Review::with(['reviewer', 'reviewedUser', 'job'])
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact('pickupModels', 'pickupJobs', 'imageUpdateModels', 'models', 'jobs', 'latestReviews'));
    }
}
