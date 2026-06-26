<?php

namespace App\Http\Controllers\Painter;

use App\Http\Controllers\Controller;
use App\Models\PainterProfile;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PainterJobApplicationController extends Controller
{
    /**
     * 自分の全依頼の全応募を一覧表示（応募者・コメント・依頼概要・採否ボタンを一目で）
     * GET /painter/applications?filter=pending|accepted|rejected
     */
    public function indexAll(Request $request): View
    {
        // 全件をクライアントに渡し、Alpine.js でリロードなしにフィルタ切替する。
        // ?filter= は初期表示用（URL 直アクセス時の初期タブ）にのみ使う。
        $filter = $request->get('filter', 'all');

        $applications = JobApplication::whereHas('job', fn($q) => $q->where('painter_id', Auth::id()))
            ->with(['job', 'model.modelProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = [
            'all'      => $applications->count(),
            'pending'  => $applications->where('status', 'pending')->count(),
            'accepted' => $applications->where('status', 'accepted')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];

        return view('painter.applications.index', [
            'applications' => $applications,
            'filter'       => $filter,
            'counts'       => $counts,
        ]);
    }

    /**
     * 応募者一覧を表示
     */
    public function index(Job $job): View
    {
        // 自分の依頼かチェック
        if ($job->painter_id !== Auth::id()) {
            abort(403);
        }

        $applications = JobApplication::where('job_id', $job->id)
            ->with(['model.modelProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('painter.jobs.applications.index', [
            'job' => $job,
            'applications' => $applications,
        ]);
    }

    /**
     * 応募を承認
     */
    public function accept(Job $job, JobApplication $application): RedirectResponse
    {
        // 自分の依頼かチェック
        if ($job->painter_id !== Auth::id()) {
            abort(403);
        }

        // 応募がこの依頼のものかチェック
        if ($application->job_id !== $job->id) {
            abort(404);
        }

        $application->update([
            'status' => 'accepted',
        ]);

        // 通知を作成（モデルに通知）
        NotificationService::notifyApplicationAccepted($application);

        // 遷移元（受け取った応募一覧 / 依頼別応募者一覧 など）にそのまま戻す
        return back()->with('success', '応募を採用しました。モデルに通知しました。');
    }

    /**
     * 応募を却下
     */
    public function reject(Job $job, JobApplication $application): RedirectResponse
    {
        // 自分の依頼かチェック
        if ($job->painter_id !== Auth::id()) {
            abort(403);
        }

        // 応募がこの依頼のものかチェック
        if ($application->job_id !== $job->id) {
            abort(404);
        }

        $application->update([
            'status' => 'rejected',
        ]);

        // 通知を作成（モデルに通知）
        NotificationService::notifyApplicationRejected($application);

        // 遷移元（受け取った応募一覧 / 依頼別応募者一覧 など）にそのまま戻す
        return back()->with('success', '応募を辞退しました。モデルに通知しました。');
    }
}
