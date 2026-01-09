<?php

namespace App\Http\Controllers\Painter;

use App\Http\Controllers\Controller;
use App\Models\PainterProfile;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PainterJobApplicationController extends Controller
{
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

        return redirect()->route('painter.jobs.applications.index', $job)
            ->with('success', '応募を承認しました');
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

        return redirect()->route('painter.jobs.applications.index', $job)
            ->with('success', '応募を却下しました');
    }
}
