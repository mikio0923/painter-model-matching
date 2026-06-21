<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\ModelProfile;
use App\Models\Job;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * お気に入り一覧を表示
     */
    public function index(): View
    {
        $user = Auth::user();

        $favorites = Favorite::where('user_id', $user->id)
            ->with(['favoritable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }

    /**
     * お気に入りを追加（モデル）
     */
    public function storeModel(Request $request, ModelProfile $modelProfile): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'painter') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'モデルのお気に入りは画家のみ可能です'], 403);
            }
            return back()->with('error', 'モデルのお気に入りは画家のみ可能です');
        }

        // 既にお気に入りに登録されているかチェック
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', ModelProfile::class)
            ->where('favoritable_id', $modelProfile->id)
            ->first();

        if ($existingFavorite) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '既にお気に入りに登録されています'], 422);
            }
            return back()->with('error', '既にお気に入りに登録されています');
        }

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $modelProfile->id,
        ]);

        NotificationService::notifyModelFavorited($modelProfile, $user);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'お気に入りに追加しました');
    }

    /**
     * お気に入りを追加（依頼）
     */
    public function storeJob(Request $request, Job $job): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'model') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '依頼のお気に入りはモデルのみ可能です'], 403);
            }
            return back()->with('error', '依頼のお気に入りはモデルのみ可能です');
        }

        // 既にお気に入りに登録されているかチェック
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Job::class)
            ->where('favoritable_id', $job->id)
            ->first();

        if ($existingFavorite) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '既にお気に入りに登録されています'], 422);
            }
            return back()->with('error', '既にお気に入りに登録されています');
        }

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Job::class,
            'favoritable_id' => $job->id,
        ]);

        NotificationService::notifyJobFavorited($job, $user);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'お気に入りに追加しました');
    }

    /**
     * お気に入りを削除
     */
    public function destroy(Favorite $favorite): RedirectResponse
    {
        $user = Auth::user();

        // 自分のお気に入りかチェック
        if ($favorite->user_id !== $user->id) {
            abort(403);
        }

        $favorite->delete();

        return back()->with('success', 'お気に入りを削除しました');
    }

    /**
     * お気に入りトグル（JS用・統一エンドポイント）
     * - target_type: 'model' | 'job'
     * - target_id: 数値
     * 戻り値: { favorited: bool, count: int }
     */
    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', 'string', 'in:model,job'],
            'target_id' => ['required', 'integer'],
        ]);

        $user = Auth::user();

        // 同ロール間の Fav は禁止: モデルのお気に入りは画家のみ / 依頼のお気に入りはモデルのみ
        if ($validated['target_type'] === 'model' && $user->role !== 'painter') {
            return response()->json(['success' => false, 'message' => 'モデルのお気に入りは画家のみ可能です'], 403);
        }
        if ($validated['target_type'] === 'job' && $user->role !== 'model') {
            return response()->json(['success' => false, 'message' => '依頼のお気に入りはモデルのみ可能です'], 403);
        }

        $modelClass = $validated['target_type'] === 'model'
            ? ModelProfile::class
            : Job::class;

        // 対象が存在するか確認
        $target = $modelClass::find($validated['target_id']);
        if (!$target) {
            return response()->json(['success' => false, 'message' => '対象が見つかりません'], 404);
        }

        $existing = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', $modelClass)
            ->where('favoritable_id', $target->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_type' => $modelClass,
                'favoritable_id' => $target->id,
            ]);
            $favorited = true;

            // 追加された時のみ通知（解除時は通知しない）
            if ($validated['target_type'] === 'model') {
                NotificationService::notifyModelFavorited($target, $user);
            } else {
                NotificationService::notifyJobFavorited($target, $user);
            }
        }

        $count = Favorite::where('favoritable_type', $modelClass)
            ->where('favoritable_id', $target->id)
            ->count();

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'count' => $count,
        ]);
    }

    /**
     * お気に入りを削除（モデル）
     */
    public function destroyModel(Request $request, ModelProfile $modelProfile): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', ModelProfile::class)
            ->where('favoritable_id', $modelProfile->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'お気に入りを削除しました');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'お気に入りが見つかりませんでした'], 422);
        }
        return back()->with('error', 'お気に入りが見つかりませんでした');
    }

    /**
     * お気に入りを削除（依頼）
     */
    public function destroyJob(Request $request, Job $job): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Job::class)
            ->where('favoritable_id', $job->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'お気に入りを削除しました');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'お気に入りが見つかりませんでした'], 422);
        }
        return back()->with('error', 'お気に入りが見つかりませんでした');
    }
}
