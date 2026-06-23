<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
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
            ->whereDoesntHave('pendingOffers') // pending offer がある依頼は一時的に非公開
            ->with('painter.painterProfile')
            ->withCount('applications');

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

        $favoriteJobIds = [];
        if (Auth::check()) {
            $favoriteJobIds = Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', Job::class)
                ->pluck('favoritable_id')
                ->all();
        }

        // 都道府県リスト（検索フォーム用・キャッシュ）
        $prefectures = cache()->remember('job_prefectures', 3600, function () {
            return Job::where('status', 'open')
                ->whereNotNull('prefecture')
                ->distinct()
                ->pluck('prefecture')
                ->sort()
                ->values();
        });

        return view('jobs.index', compact('jobs', 'prefectures', 'favoriteJobIds'));
    }

    /**
     * 依頼詳細を表示
     */
    public function show(Job $job): View
    {
        $isOwner = Auth::check() && Auth::id() === $job->painter_id;
        $hasRelatedOffer = Auth::check()
            && $job->offers()->where('model_id', Auth::id())->exists();

        // 取引完了済（応募が accepted かつ payment_received_at セット）なら「実績」として公開
        $isCompletedJob = $job->applications()
            ->where('status', 'accepted')
            ->whereNotNull('payment_received_at')
            ->exists();

        // closed/done の依頼は、所有画家本人 / 個別依頼の対象モデル / 取引完了済（公開実績）のみ閲覧可
        if ($job->status !== 'open' && !$isOwner && !$hasRelatedOffer && !$isCompletedJob) {
            abort(404);
        }

        // open でも pending な個別依頼がある場合は、画家本人と指名されたモデルのみ閲覧可
        if ($job->status === 'open' && $job->pendingOffers()->exists()) {
            $allowed = $isOwner
                || ($hasRelatedOffer
                    && $job->pendingOffers()->where('model_id', Auth::id())->exists());
            if (!$allowed) {
                abort(404);
            }
        }

        $job->load(
            'painter.painterProfile',
            'applications.model.modelProfile',
            'reviews.reviewer.painterProfile',
            'reviews.reviewer.modelProfile',
            'reviews.reviewedUser.painterProfile',
            'reviews.reviewedUser.modelProfile'
        );

        // ログインユーザーが既に応募しているかチェック
        $hasApplied = false;
        if (Auth::check() && Auth::user()->role === 'model') {
            $hasApplied = JobApplication::where('job_id', $job->id)
                ->where('model_id', Auth::id())
                ->exists();
        }

        // レビュー / 取引完了の状態を判定
        // - acceptedApplication: 承認された応募
        // - canMarkComplete: モデル側で「報酬を受け取りました」を押せる状態
        // - canReview: 取引完了済みでレビュー未投稿なら true
        $canReview         = false;
        $reviewTarget      = null;
        $acceptedApplication = null;
        $canMarkComplete   = false;
        if (Auth::check()) {
            if (Auth::user()->role === 'model') {
                $acceptedApplication = JobApplication::where('job_id', $job->id)
                    ->where('model_id', Auth::id())
                    ->where('status', 'accepted')
                    ->first();
                if ($acceptedApplication) {
                    $reviewTarget = $job->painter;
                    $canReview    = $acceptedApplication->isCompleted();
                    // 取引完了の宣言は撮影日当日以降のみ可
                    if (!$acceptedApplication->isCompleted()) {
                        $scheduled = $job->scheduled_date;
                        $canMarkComplete = !$scheduled
                            || $scheduled->isPast()
                            || $scheduled->isToday();
                    }
                }
            } else {
                $acceptedApplication = JobApplication::where('job_id', $job->id)
                    ->where('status', 'accepted')
                    ->first();
                if ($acceptedApplication && $job->painter_id === Auth::id()) {
                    $reviewTarget = $acceptedApplication->model;
                    $canReview    = $acceptedApplication->isCompleted();
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

        return view('jobs.show', compact('job', 'hasApplied', 'canReview', 'reviewTarget', 'isFavorite', 'acceptedApplication', 'canMarkComplete'));
    }
}
