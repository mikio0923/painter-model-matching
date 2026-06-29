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

        // 募集中の依頼（最大3件のみ表示。残りは「もっと見る」リンクから過去依頼一覧へ）
        $openJobsQuery = Job::where('painter_id', $painterProfile->user_id)
            ->where('status', 'open');
        $openJobsTotal = (clone $openJobsQuery)->count();
        $openJobs = $openJobsQuery->latest()->take(3)->get();

        // 過去の完了依頼（取引完了済の応募が紐づくもの）— 上位3件のみプロフ画面で表示
        $pastJobsQuery = Job::where('painter_id', $painterProfile->user_id)
            ->whereIn('status', ['closed', 'done'])
            ->whereHas('applications', function ($q) {
                $q->where('status', 'accepted')
                  ->whereNotNull('payment_received_at');
            });
        $pastJobsTotal = (clone $pastJobsQuery)->count();
        $pastJobs = $pastJobsQuery->latest()->take(3)->get();

        // この画家が受け取ったレビューの統計
        $reviewStats = Review::where('reviewed_user_id', $painterProfile->user_id)
            ->selectRaw('COUNT(*) as cnt, AVG(rating) as avg_rating')
            ->first();

        $reviewCount = (int) ($reviewStats->cnt ?? 0);
        $reviewAvg   = $reviewStats?->avg_rating !== null
            ? round((float) $reviewStats->avg_rating, 1)
            : null;

        // 最新レビュー数件（依頼名 / 依頼へのリンクを出すために job も eager load）
        $latestReviews = Review::where('reviewed_user_id', $painterProfile->user_id)
            ->with(['reviewer', 'job'])
            ->latest()
            ->take(3)
            ->get();

        return view('painters.show', [
            'painterProfile' => $painterProfile,
            'painter'        => $painterProfile->user,
            'openJobs'       => $openJobs,
            'openJobsTotal'  => $openJobsTotal,
            'pastJobs'       => $pastJobs,
            'pastJobsTotal'  => $pastJobsTotal,
            'reviewCount'    => $reviewCount,
            'reviewAvg'      => $reviewAvg,
            'latestReviews'  => $latestReviews,
        ]);
    }

    /**
     * 画家の過去募集一覧（取引完了済を含む全実績）
     */
    public function jobs(PainterProfile $painterProfile): View
    {
        $painterProfile->load('user');

        $jobs = Job::where('painter_id', $painterProfile->user_id)
            ->whereIn('status', ['closed', 'done'])
            ->whereHas('applications', function ($q) {
                $q->where('status', 'accepted')
                  ->whereNotNull('payment_received_at');
            })
            ->latest()
            ->paginate(20);

        return view('painters.jobs', [
            'painterProfile' => $painterProfile,
            'painter'        => $painterProfile->user,
            'jobs'           => $jobs,
        ]);
    }
}
