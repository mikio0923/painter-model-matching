<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Review;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * レビュー投稿画面を表示
     */
    public function create(Request $request, Job $job): View
    {
        $user = Auth::user();

        // 依頼の相手を取得
        $otherUser = null;
        if ($user->role === 'model') {
            // モデルの場合、画家にレビューを書く
            $otherUser = $job->painter;
        } else {
            // 画家の場合、承認されたモデルにレビューを書く
            $application = JobApplication::where('job_id', $job->id)
                ->where('status', 'accepted')
                ->first();
            if ($application) {
                $otherUser = $application->model;
            }
        }

        if (!$otherUser) {
            abort(404, 'レビューを書く相手が見つかりません');
        }

        // 既にレビューを書いているかチェック
        $existingReview = Review::where('job_id', $job->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewed_user_id', $otherUser->id)
            ->first();

        return view('reviews.create', [
            'job' => $job,
            'otherUser' => $otherUser,
            'existingReview' => $existingReview,
        ]);
    }

    /**
     * レビューを保存
     */
    public function store(Request $request, Job $job): RedirectResponse
    {
        $request->validate([
            'reviewed_user_id' => ['required', 'integer', 'exists:users,id'],
            'rating' => ['required', 'string', 'in:very_good,good,bad'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = Auth::user();

        // 既にレビューを書いているかチェック
        $existingReview = Review::where('job_id', $job->id)
            ->where('reviewer_id', $user->id)
            ->where('reviewed_user_id', $request->reviewed_user_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', '既にレビューを投稿済みです');
        }

        Review::create([
            'job_id' => $job->id,
            'reviewer_id' => $user->id,
            'reviewed_user_id' => $request->reviewed_user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('success', 'レビューを投稿しました');
    }
}
