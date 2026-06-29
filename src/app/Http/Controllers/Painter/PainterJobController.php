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
    public function index(Request $request): View
    {
        $query = Job::where('painter_id', Auth::id())
            ->with(['painter.painterProfile', 'applications.model.modelProfile'])
            ->withCount('applications');

        $status = $request->query('status');
        if (in_array($status, ['open', 'closed', 'done'], true)) {
            $query->where('status', $status);
        }

        $jobs = $query->orderBy('created_at', 'desc')->get();

        return view('painter.jobs.index', [
            'jobs' => $jobs,
            'currentStatus' => $status,
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
            'prefectures' => self::prefectures(),
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
            'transportation_fee' => $validated['transportation_fee'] ?? null,
            'costume_provided' => $validated['costume_provided'] ?? null,
            'target' => $validated['target'] ?? null,
            'recruitment_number' => 1, // 募集は常に1名に固定
            'location_type' => $validated['location_type'],
            'prefecture' => $validated['prefecture'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'access' => $validated['access'] ?? null,
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
            'prefectures' => self::prefectures(),
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
            'transportation_fee' => $validated['transportation_fee'] ?? null,
            'costume_provided' => $validated['costume_provided'] ?? null,
            'target' => $validated['target'] ?? null,
            'recruitment_number' => 1, // 募集は常に1名に固定
            'location_type' => $validated['location_type'],
            'prefecture' => $validated['prefecture'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'access' => $validated['access'] ?? null,
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'apply_deadline' => $validated['apply_deadline'] ?? null,
            'status' => $validated['status'] ?? $job->status,
        ]);

        return redirect()->route('painter.jobs.index')
            ->with('success', '依頼を更新しました');
    }

    /**
     * 依頼を削除
     */
    public function destroy(Job $job): RedirectResponse
    {
        if ($job->painter_id !== Auth::id()) {
            abort(403);
        }

        // 公開中 (open) の依頼で採用済みの応募がある場合のみ削除を防止する
        // 締切 (closed) や完了 (done) は応募有無に関わらず削除可（履歴整理用）
        if ($job->status === 'open') {
            $hasAccepted = $job->applications()->where('status', 'accepted')->exists();
            if ($hasAccepted) {
                return redirect()->route('painter.jobs.index')
                    ->with('error', '公開中で採用済みの応募がある依頼は削除できません。先に依頼を締切ってください。');
            }
        }

        $job->delete();

        return redirect()->route('painter.jobs.index')
            ->with('success', '依頼を削除しました');
    }

    private static function prefectures(): array
    {
        return [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
        ];
    }
}
