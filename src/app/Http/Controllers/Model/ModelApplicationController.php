<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModelApplicationController extends Controller
{
    /**
     * 応募一覧を表示
     */
    public function index(): View
    {
        $applications = JobApplication::where('model_id', Auth::id())
            ->with(['job.painter.painterProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('model.applications.index', [
            'applications' => $applications,
        ]);
    }

    /**
     * 依頼に応募
     */
    public function apply(Request $request, Job $job): RedirectResponse
    {
        $user = Auth::user();

        // 既に応募しているかチェック
        $existingApplication = JobApplication::where('job_id', $job->id)
            ->where('model_id', $user->id)
            ->first();

        if ($existingApplication) {
            return redirect()->route('jobs.show', $job)
                ->with('error', '既に応募済みです');
        }

        // 応募を作成
        $application = JobApplication::create([
            'job_id' => $job->id,
            'model_id' => $user->id,
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        // 通知を作成（画家に通知）
        NotificationService::notifyApplicationReceived($application);

        return redirect()->route('model.applications.index')
            ->with('success', '応募が完了しました');
    }

    /**
     * モデルが「報酬を受け取った」と宣言して取引を完了する。
     * 撮影日 (scheduled_date) 当日以降のみ実行可能。
     * 取引完了後に双方がレビューを投稿できるようになる。
     */
    public function markPaymentReceived(Request $request, JobApplication $application): RedirectResponse
    {
        // 自分の応募かチェック
        abort_unless($application->model_id === Auth::id(), 403);

        // 既に完了済みなら何もしない
        if ($application->isCompleted()) {
            return redirect()->route('jobs.show', $application->job)
                ->with('error', 'すでに取引完了済みです。');
        }

        // 承認されているか
        if ($application->status !== 'accepted') {
            return redirect()->route('jobs.show', $application->job)
                ->with('error', '承認された応募のみ取引完了にできます。');
        }

        // 撮影日当日以降のみ
        $scheduled = $application->job->scheduled_date;
        if ($scheduled && $scheduled->isFuture() && !$scheduled->isToday()) {
            return redirect()->route('jobs.show', $application->job)
                ->with('error', '撮影日当日以降に取引完了の操作ができます。');
        }

        $application->update(['payment_received_at' => now()]);

        return redirect()->route('jobs.show', $application->job)
            ->with('success', '取引完了を確定しました。レビューを投稿できます。');
    }
}
