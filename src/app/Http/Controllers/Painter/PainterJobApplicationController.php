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

        // 各 application に「この依頼の枠が満員か」を付与（UI で採用ボタンを非活性化するため）
        $jobIds = $applications->pluck('job_id')->unique()->all();
        $acceptedCounts = JobApplication::whereIn('job_id', $jobIds)
            ->where('status', 'accepted')
            ->selectRaw('job_id, COUNT(*) as c')
            ->groupBy('job_id')
            ->pluck('c', 'job_id')
            ->all();
        $applications->each(function (JobApplication $app) use ($acceptedCounts) {
            $limit = (int) ($app->job->recruitment_number ?? 1);
            $accepted = (int) ($acceptedCounts[$app->job_id] ?? 0);
            $app->job_is_full = $accepted >= $limit;
            $app->job_accepted_count = $accepted;
            $app->job_limit = $limit;
        });

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

        // 採用済が募集人数に達していたら弾く（達した瞬間以降は採用不可）
        $limit = (int) ($job->recruitment_number ?? 1);
        $acceptedCount = $job->applications()->where('status', 'accepted')->count();
        // 既に accepted な応募を再度 accept する操作は意味がないので素通し
        if ($application->status !== 'accepted' && $acceptedCount >= $limit) {
            return back()->with('error', "募集人数（{$limit}名）に達しているため、これ以上採用できません。");
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
