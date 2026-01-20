<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * 依頼一覧を表示
     */
    public function index(Request $request): View
    {
        $query = Job::where('status', 'open')
            ->with('painter.painterProfile');

        // 都道府県で検索
        if ($request->filled('prefecture')) {
            $query->where('prefecture', $request->prefecture);
        }

        // 場所タイプで検索
        if ($request->filled('location_type')) {
            $query->where('location_type', $request->location_type);
        }

        // 報酬範囲で検索
        if ($request->filled('reward_min')) {
            $query->where('reward_amount', '>=', $request->reward_min);
        }
        if ($request->filled('reward_max')) {
            $query->where('reward_amount', '<=', $request->reward_max);
        }

        // キーワード検索（タイトル・説明）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // ソート
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'reward_high':
                $query->orderBy('reward_amount', 'desc');
                break;
            case 'reward_low':
                $query->orderBy('reward_amount', 'asc');
                break;
            case 'deadline':
                $query->orderBy('apply_deadline', 'asc');
                break;
            default:
                $query->latest();
        }

        $jobs = $query->paginate(12)->withQueryString();

        // 都道府県リスト（検索フォーム用・キャッシュ）
        $prefectures = cache()->remember('job_prefectures', 3600, function () {
            return Job::where('status', 'open')
                ->whereNotNull('prefecture')
                ->distinct()
                ->pluck('prefecture')
                ->sort()
                ->values();
        });

        return view('jobs.index', compact('jobs', 'prefectures'));
    }

    /**
     * 依頼詳細を表示
     */
    public function show(Job $job): View
    {
        // 非公開の依頼は404
        if ($job->status !== 'open') {
            abort(404);
        }

        $job->load('painter.painterProfile', 'applications.model.modelProfile', 'reviews.reviewer', 'reviews.reviewedUser');

        // ログインユーザーが既に応募しているかチェック
        $hasApplied = false;
        if (Auth::check() && Auth::user()->role === 'model') {
            $hasApplied = JobApplication::where('job_id', $job->id)
                ->where('model_id', Auth::id())
                ->exists();
        }

        // レビューを書けるかチェック（承認された応募がある場合）
        $canReview = false;
        $reviewTarget = null;
        if (Auth::check()) {
            if (Auth::user()->role === 'model') {
                $application = JobApplication::where('job_id', $job->id)
                    ->where('model_id', Auth::id())
                    ->where('status', 'accepted')
                    ->first();
                if ($application) {
                    $canReview = true;
                    $reviewTarget = $job->painter;
                }
            } else {
                $application = JobApplication::where('job_id', $job->id)
                    ->where('status', 'accepted')
                    ->first();
                if ($application && $job->painter_id === Auth::id()) {
                    $canReview = true;
                    $reviewTarget = $application->model;
                }
            }
        }

        // お気に入り状態を取得
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', Job::class)
                ->where('favoritable_id', $job->id)
                ->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied', 'canReview', 'reviewTarget', 'isFavorite'));
    }
}
