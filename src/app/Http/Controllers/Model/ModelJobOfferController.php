<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ModelJobOfferController extends Controller
{
    public function index(): View
    {
        $pendingOffers = JobOffer::where('model_id', Auth::id())
            ->where('status', JobOffer::STATUS_PENDING)
            ->with(['job.painter.painterProfile'])
            ->latest()
            ->get();

        $pastOffers = JobOffer::where('model_id', Auth::id())
            ->whereIn('status', [JobOffer::STATUS_ACCEPTED, JobOffer::STATUS_DECLINED])
            ->with(['job.painter.painterProfile'])
            ->latest('responded_at')
            ->take(50)
            ->get();

        return view('model.job-offers.index', compact('pendingOffers', 'pastOffers'));
    }

    public function accept(Request $request, JobOffer $offer): RedirectResponse
    {
        abort_unless($offer->model_id === Auth::id(), 403);

        if (!$offer->isPending()) {
            return redirect()->route('model.job-offers.index')
                ->with('error', 'この個別依頼にはすでに返答しています。');
        }

        $request->validate([
            'model_response' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($offer, $request) {
            // Offer を accepted へ
            $offer->update([
                'status'         => JobOffer::STATUS_ACCEPTED,
                'model_response' => $request->input('model_response'),
                'responded_at'   => now(),
            ]);

            // 既存の応募と統合: JobApplication を作成（既存なら更新）
            JobApplication::updateOrCreate(
                [
                    'job_id'   => $offer->job_id,
                    'model_id' => $offer->model_id,
                ],
                [
                    'message' => $request->input('model_response') ?: '個別依頼から受諾',
                    'status'  => 'accepted',
                ]
            );

            // 受諾された依頼は closed にして他の応募を締切
            $offer->job()->update(['status' => 'closed']);
        });

        NotificationService::notifyOfferAccepted($offer->fresh(['job.painter', 'model.modelProfile']));

        return redirect()->route('model.job-offers.index')
            ->with('success', '個別依頼を受諾しました。画家に通知されます。');
    }

    public function decline(Request $request, JobOffer $offer): RedirectResponse
    {
        abort_unless($offer->model_id === Auth::id(), 403);

        if (!$offer->isPending()) {
            return redirect()->route('model.job-offers.index')
                ->with('error', 'この個別依頼にはすでに返答しています。');
        }

        $request->validate([
            'model_response' => ['nullable', 'string', 'max:2000'],
        ]);

        $offer->update([
            'status'         => JobOffer::STATUS_DECLINED,
            'model_response' => $request->input('model_response'),
            'responded_at'   => now(),
        ]);

        // Job の公開状態は触らない（pending が無くなれば JobController 側のクエリで自動的に公開復帰）
        NotificationService::notifyOfferDeclined($offer->fresh(['job.painter', 'model.modelProfile']));

        return redirect()->route('model.job-offers.index')
            ->with('success', '個別依頼を辞退しました。画家に丁寧にお伝えします。');
    }
}
