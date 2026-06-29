<?php

namespace App\Http\Controllers\Painter;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobOffer;
use App\Models\ModelProfile;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PainterJobOfferController extends Controller
{
    /**
     * 個別依頼の作成画面
     * GET /painter/job-offers/create?model={modelProfileId}
     */
    public function create(Request $request): View|RedirectResponse
    {
        $modelProfileId = $request->integer('model');
        $modelProfile   = ModelProfile::with('user')->find($modelProfileId);

        if (!$modelProfile || !$modelProfile->is_public) {
            return redirect()->route('models.index')
                ->with('error', '指定のモデルが見つかりませんでした。');
        }

        $modelUserId = $modelProfile->user_id;

        // 同じモデルへの過去オファー（再オファー不可判定）
        $existingOffer = JobOffer::whereHas('job', fn($q) => $q->where('painter_id', Auth::id()))
            ->where('model_id', $modelUserId)
            ->latest()
            ->first();

        if ($existingOffer && $existingOffer->isPending()) {
            return redirect()->route('models.show', $modelProfile)
                ->with('error', 'このモデルにはすでに個別依頼を送信しています。返答をお待ちください。');
        }

        // 個別依頼として送れる依頼:
        //   1) open であること
        //   2) このモデルへの既存オファーが無い
        //   3) 他モデルへの pending オファーが無い
        //   4) 採用済み (accepted) の応募が無い ← 募集が事実上埋まっている依頼は除外
        $availableJobs = Job::where('painter_id', Auth::id())
            ->where('status', 'open')
            ->whereDoesntHave('offers', fn($q) =>
                $q->where('model_id', $modelUserId)
            )
            ->whereDoesntHave('pendingOffers')
            ->whereDoesntHave('applications', fn($q) =>
                $q->where('status', 'accepted')
            )
            ->latest()
            ->get();

        return view('painter.job-offers.create', [
            'modelProfile'  => $modelProfile,
            'availableJobs' => $availableJobs,
        ]);
    }

    /**
     * 個別依頼を送信
     * - mode=existing → 既存 job を選んでオファー
     * - mode=new → 新規 job を作成画面に飛ばし、そこから offer 化
     *
     * 新規作成は既存の依頼作成画面を再利用するために、ここでは existing のみ受け付ける。
     * 新規はフロントで /painter/jobs/create?for_model={id} に遷移し、保存後の hook で
     * offer を作る形にする（PainterJobController::store 内でハンドル）。
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'job_id'          => ['required', 'integer', 'exists:painter_jobs,id'],
            'model_id'        => ['required', 'integer', 'exists:users,id'],
            'painter_message' => ['nullable', 'string', 'max:2000'],
        ]);

        $job = Job::findOrFail($validated['job_id']);
        abort_unless($job->painter_id === Auth::id(), 403);

        $modelUser = User::findOrFail($validated['model_id']);
        abort_unless($modelUser->role === 'model', 422);

        // 再オファー不可（同 job × 同 model）
        $alreadyOffered = JobOffer::where('job_id', $job->id)
            ->where('model_id', $modelUser->id)
            ->exists();

        if ($alreadyOffered) {
            return redirect()->route('models.show', $modelUser->modelProfile)
                ->with('error', 'この依頼はすでにこのモデルへ送信済みです。');
        }

        $offer = JobOffer::create([
            'job_id'          => $job->id,
            'model_id'        => $modelUser->id,
            'painter_message' => $validated['painter_message'] ?? null,
            'status'          => JobOffer::STATUS_PENDING,
        ]);

        NotificationService::notifyOfferReceived($offer);

        // painter.jobs.show は存在しないため、画家側の個別依頼一覧へ
        return redirect()->route('painter.job-offers.index')
            ->with('success', '個別依頼を送信しました。モデルからの返答をお待ちください。');
    }

    /**
     * 自分（画家）が送ったオファー一覧
     */
    public function index(): View
    {
        $offers = JobOffer::whereHas('job', fn($q) => $q->where('painter_id', Auth::id()))
            ->with(['job', 'model.modelProfile'])
            ->latest()
            ->paginate(20);

        return view('painter.job-offers.index', compact('offers'));
    }
}
