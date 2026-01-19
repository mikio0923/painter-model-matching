<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\ModelProfile;
use App\Models\Job;
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
    public function storeModel(Request $request, ModelProfile $modelProfile): RedirectResponse
    {
        $user = Auth::user();

        // 既にお気に入りに登録されているかチェック
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', ModelProfile::class)
            ->where('favoritable_id', $modelProfile->id)
            ->first();

        if ($existingFavorite) {
            return back()->with('error', '既にお気に入りに登録されています');
        }

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id' => $modelProfile->id,
        ]);

        return back()->with('success', 'お気に入りに追加しました');
    }

    /**
     * お気に入りを追加（依頼）
     */
    public function storeJob(Request $request, Job $job): RedirectResponse
    {
        $user = Auth::user();

        // 既にお気に入りに登録されているかチェック
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Job::class)
            ->where('favoritable_id', $job->id)
            ->first();

        if ($existingFavorite) {
            return back()->with('error', '既にお気に入りに登録されています');
        }

        Favorite::create([
            'user_id' => $user->id,
            'favoritable_type' => Job::class,
            'favoritable_id' => $job->id,
        ]);

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
     * お気に入りを削除（モデル）
     */
    public function destroyModel(ModelProfile $modelProfile): RedirectResponse
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', ModelProfile::class)
            ->where('favoritable_id', $modelProfile->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'お気に入りを削除しました');
        }

        return back()->with('error', 'お気に入りが見つかりませんでした');
    }

    /**
     * お気に入りを削除（依頼）
     */
    public function destroyJob(Job $job): RedirectResponse
    {
        $user = Auth::user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', Job::class)
            ->where('favoritable_id', $job->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'お気に入りを削除しました');
        }

        return back()->with('error', 'お気に入りが見つかりませんでした');
    }
}
