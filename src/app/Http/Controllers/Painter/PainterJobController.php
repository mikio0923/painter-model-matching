<?php

namespace App\Http\Controllers\Painter;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\PainterProfile;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\ModelProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PainterJobController extends Controller
{
    /**
     * 依頼一覧を表示
     */
    public function index(): View
    {
        $jobs = Job::where('painter_id', Auth::id())
            ->with(['painter.painterProfile', 'applications.model.modelProfile'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('painter.jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * 依頼作成画面を表示
     */
    public function create(Request $request): View
    {
        $modelProfile = null;

        if ($request->has('model_id')) {
            $modelProfile = ModelProfile::find($request->model_id);
        }

        return view('painter.jobs.create', [
            'modelProfile' => $modelProfile,
        ]);
    }

    /**
     * 依頼を保存
     */
    public function store(StoreJobRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $job = Job::create([
            'painter_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'usage_purpose' => $validated['usage_purpose'] ?? null,
            'reward_amount' => $validated['reward_amount'] ?? null,
            'reward_unit' => $validated['reward_unit'] ?? 'per_session',
            'location_type' => $validated['location_type'],
            'prefecture' => $validated['prefecture'] ?? null,
            'city' => $validated['city'] ?? null,
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'apply_deadline' => $validated['apply_deadline'] ?? null,
            'status' => 'open',
        ]);

        return redirect()->route('painter.jobs.index')
            ->with('success', '依頼を作成しました');
    }

    /**
     * 依頼編集画面を表示
     */
    public function edit(Job $job): View
    {
        // 自分の依頼かチェック
        if ($job->painter_id !== Auth::id()) {
            abort(403);
        }

        return view('painter.jobs.edit', [
            'job' => $job,
        ]);
    }

    /**
     * 依頼を更新
     */
    public function update(UpdateJobRequest $request, Job $job): RedirectResponse
    {
        // 自分の依頼かチェック
        if ($job->painter_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $job->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'usage_purpose' => $validated['usage_purpose'] ?? null,
            'reward_amount' => $validated['reward_amount'] ?? null,
            'reward_unit' => $validated['reward_unit'] ?? 'per_session',
            'location_type' => $validated['location_type'],
            'prefecture' => $validated['prefecture'] ?? null,
            'city' => $validated['city'] ?? null,
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'apply_deadline' => $validated['apply_deadline'] ?? null,
            'status' => $validated['status'] ?? $job->status,
        ]);

        return redirect()->route('painter.jobs.index')
            ->with('success', '依頼を更新しました');
    }
}
