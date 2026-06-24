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
     * クエリ ?filter=applying|closed|done で 応募中 / 締切 / 完了 に絞り込み可能。
     *
     * 派生ステータス（依頼の状態と連動）:
     *  - 完了 (done)    : payment_received_at がセット済み or job.status === 'done'
     *  - 締切 (closed)  : job.status === 'closed'（完了未確定）
     *  - 採用 (accepted): job 開催中で app.status='accepted'
     *  - 辞退 (rejected): app.status='rejected'
     *  - 応募中 (applying): それ以外（job が open かつ pending）
     */
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');

        $applications = JobApplication::where('model_id', Auth::id())
            ->with(['job.painter.painterProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 派生ステータスを各 application に付与
        $applications->each(function (JobApplication $app) {
            $app->display_status = $this->resolveDisplayStatus($app);
        });

        // フィルタリング
        $filtered = $applications;
        if (in_array($filter, ['applying', 'closed', 'done'], true)) {
            $filtered = $applications->filter(fn(JobApplication $app) =>
                $this->matchesFilter($app->display_status, $filter)
            )->values();
        }

        // 件数カウント
        $counts = [
            'all'      => $applications->count(),
            'applying' => $applications->filter(fn($a) => $this->matchesFilter($a->display_status, 'applying'))->count(),
            'closed'   => $applications->filter(fn($a) => $this->matchesFilter($a->display_status, 'closed'))->count(),
            'done'     => $applications->filter(fn($a) => $this->matchesFilter($a->display_status, 'done'))->count(),
        ];

        return view('model.applications.index', [
            'applications' => $filtered,
            'filter'       => $filter,
            'counts'       => $counts,
        ]);
    }

    /**
     * 応募の派生ステータスを判定
     * 戻り値: 'done' | 'closed' | 'accepted' | 'rejected' | 'applying'
     */
    private function resolveDisplayStatus(JobApplication $app): string
    {
        if ($app->payment_received_at !== null) {
            return 'done';
        }
        $jobStatus = $app->job?->status;
        if ($jobStatus === 'done') {
            return 'done';
        }
        if ($jobStatus === 'closed') {
            return 'closed';
        }
        if ($app->status === 'rejected') {
            return 'rejected';
        }
        if ($app->status === 'accepted') {
            return 'accepted';
        }
        return 'applying';
    }

    /**
     * 「応募・締切・完了」3 フィルタへの集約マッピング
     * applying: 'applying' + 'accepted'（採用済みも撮影日まで「応募中（採用済）」扱い）
     * closed:   'closed' + 'rejected'（依頼締切と辞退は履歴扱いに）
     * done:     'done'
     */
    private function matchesFilter(string $displayStatus, string $filter): bool
    {
        return match ($filter) {
            'applying' => in_array($displayStatus, ['applying', 'accepted'], true),
            'closed'   => in_array($displayStatus, ['closed', 'rejected'], true),
            'done'     => $displayStatus === 'done',
            default    => true,
        };
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
