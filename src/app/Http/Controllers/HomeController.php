<?php

namespace App\Http\Controllers;

use App\Models\ModelProfile;
use App\Models\Job;
use App\Models\Information;

class HomeController extends Controller
{
    public function index()
    {
        // Pickup Model（ランダムに12件、画像があるものを優先）
        $pickupModels = ModelProfile::where('is_public', true)
            ->whereNotNull('profile_image_path')
            ->with('user')
            ->inRandomOrder()
            ->take(12)
            ->get();

        // 画像がない場合は画像なしも含める
        if ($pickupModels->count() < 12) {
            $additionalModels = ModelProfile::where('is_public', true)
                ->whereNull('profile_image_path')
                ->with('user')
                ->inRandomOrder()
                ->take(12 - $pickupModels->count())
                ->get();
            $pickupModels = $pickupModels->merge($additionalModels);
        }

        // Pickup Job（ランダムに5件）
        $pickupJobs = Job::where('status', 'open')
            ->with('painter.painterProfile')
            ->inRandomOrder()
            ->take(5)
            ->get();

        // Image Update Profile（最近画像を更新したモデル、画像があるもの）
        $imageUpdateModels = ModelProfile::where('is_public', true)
            ->whereNotNull('profile_image_path')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(12)
            ->get();

        // 新着モデル（公開のみ）
        $models = ModelProfile::where('is_public', true)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        // 新着依頼
        $jobs = Job::where('status', 'open')
            ->with('painter.painterProfile')
            ->latest()
            ->take(6)
            ->get();

        // 新着レビュー（20件、5列×4行）
        $latestReviews = \App\Models\Review::with([
                'reviewer.painterProfile',
                'reviewer.modelProfile',
                'reviewedUser.painterProfile',
                'reviewedUser.modelProfile',
                'job.painter.painterProfile'
            ])
            ->latest()
            ->take(20)
            ->get();

        // 高評価レビュー（モデル側と画家側の高評価、ランダムまたは新着順）
        $highRatingReviews = \App\Models\Review::with([
                'reviewer.painterProfile',
                'reviewer.modelProfile',
                'reviewedUser.painterProfile',
                'reviewedUser.modelProfile',
                'job.painter.painterProfile'
            ])
            ->whereIn('rating', ['very_good', 'good'])
            ->latest()
            ->take(10)
            ->get();

        // お知らせ（最新4件）
        $informations = Information::published()
            ->ofType('information')
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // プレスリリース（最新3件）
        $pressReleases = Information::published()
            ->ofType('press_release')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // 新着オファー（最新7件）
        $newJobOffers = Job::where('status', 'open')
            ->with('painter.painterProfile')
            ->latest()
            ->take(7)
            ->get();

        return view('home', compact(
            'pickupModels',
            'pickupJobs',
            'imageUpdateModels',
            'models',
            'jobs',
            'latestReviews',
            'highRatingReviews',
            'informations',
            'pressReleases',
            'newJobOffers'
        ));
    }
}
