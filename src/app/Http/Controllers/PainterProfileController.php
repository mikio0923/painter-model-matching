<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\PainterProfile;
use App\Models\Review;
use Illuminate\View\View;

class PainterProfileController extends Controller
{
    /**
     * 画家プロフィール公開ページ
     */
    public function show(PainterProfile $painterProfile): View
    {
        $painterProfile->load('user');

        // 公開中の依頼
        $openJobs = Job::where('painter_id', $painterProfile->user_id)
            ->where('status', 'open')
            ->latest()
            ->take(8)
            ->get();

        // 過去の完了依頼（取引完了済の応募が紐づくもの）
        $pastJobs = Job::where('painter_id', $painterProfile->user_id)
            ->whereIn('status', ['closed', 'done'])
            ->whereHas('applications', function ($q) {
                $q->where('status', 'accepted')
                  ->whereNotNull('payment_received_at');
            })
            ->latest()
            ->take(10)
            ->get();

        // この画家が受け取ったレビューの統計
        $reviewStats = Review::where('reviewed_user_id', $painterProfile->user_id)
            ->selectRaw('COUNT(*) as cnt, AVG(rating) as avg_rating')
            ->first();

        $reviewCount = (int) ($reviewStats->cnt ?? 0);
        $reviewAvg   = $reviewStats?->avg_rating !== null
            ? round((float) $reviewStats->avg_rating, 1)
            : null;

        // 最新レビュー数件（コメント表示用）
        $latestReviews = Review::where('reviewed_user_id', $painterProfile->user_id)
            ->with('reviewer')
            ->latest()
            ->take(3)
            ->get();

        return view('painters.show', [
            'painterProfile' => $painterProfile,
            'painter'        => $painterProfile->user,
            'openJobs'       => $openJobs,
            'pastJobs'       => $pastJobs,
            'reviewCount'    => $reviewCount,
            'reviewAvg'      => $reviewAvg,
            'latestReviews'  => $latestReviews,
        ]);
    }
}
