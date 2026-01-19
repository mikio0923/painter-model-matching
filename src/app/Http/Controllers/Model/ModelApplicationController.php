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
            ->with(['job.painter'])
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
            'status' => 'applied',
        ]);

        // 通知を作成（画家に通知）
        NotificationService::notifyApplicationReceived($application);

        return redirect()->route('model.applications.index')
            ->with('success', '応募が完了しました');
    }
}
