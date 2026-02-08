<?php

namespace App\Http\Controllers\Model;

use App\Models\ModelProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelProfileController extends Controller
{
    // 一覧（検索機能付き）
    public function index(Request $request)
    {
        $query = ModelProfile::where('is_public', true);

        // 都道府県で検索
        if ($request->filled('prefecture')) {
            $query->where('prefecture', $request->prefecture);
        }

        // 性別で検索
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // 髪型で検索
        if ($request->filled('hair_type')) {
            $query->where('hair_type', $request->hair_type);
        }

        // 年齢範囲で検索
        if ($request->filled('age_min')) {
            $query->where('age', '>=', $request->age_min);
        }
        if ($request->filled('age_max')) {
            $query->where('age', '<=', $request->age_max);
        }

        // 身長範囲で検索
        if ($request->filled('height_min')) {
            $query->where('height', '>=', $request->height_min);
        }
        if ($request->filled('height_max')) {
            $query->where('height', '<=', $request->height_max);
        }

        // 体型で検索（複数選択対応）
        if ($request->filled('body_type')) {
            $bodyTypes = is_array($request->body_type) ? $request->body_type : [$request->body_type];
            $bodyTypes = array_filter($bodyTypes); // 空の値を除外
            if (!empty($bodyTypes)) {
                $query->whereIn('body_type', $bodyTypes);
            }
        }

        // オンライン対応で検索
        if ($request->filled('online_available')) {
            $query->where('online_available', true);
        }

        // タグで検索（JSON配列内を検索・複数選択対応）
        if ($request->filled('tag')) {
            $tags = is_array($request->tag) ? $request->tag : [$request->tag];
            $tags = array_filter($tags); // 空の値を除外
            if (!empty($tags)) {
                $query->where(function($q) use ($tags) {
                    foreach ($tags as $tag) {
                        $q->orWhereJsonContains('style_tags', $tag);
                    }
                });
            }
        }

        // 報酬範囲で検索
        if ($request->filled('reward_min')) {
            $query->where(function($q) use ($request) {
                $q->where('reward_min', '<=', $request->reward_min)
                  ->orWhereNull('reward_min');
            })->where(function($q) use ($request) {
                $q->where('reward_max', '>=', $request->reward_min)
                  ->orWhereNull('reward_max');
            });
        }

        // キーワード検索（表示名）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('display_name', 'like', "%{$keyword}%");
        }

        // ソート
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'reward_high':
                $query->orderBy('reward_max', 'desc')->orderBy('reward_min', 'desc');
                break;
            case 'reward_low':
                $query->orderBy('reward_min', 'asc')->orderBy('reward_max', 'asc');
                break;
            default:
                $query->latest();
        }

        $models = $query->with('user')->paginate(36)->withQueryString();

        // 都道府県リスト（検索フォーム用・キャッシュ）
        $prefectures = cache()->remember('model_prefectures', 3600, function () {
            return ModelProfile::where('is_public', true)
                ->whereNotNull('prefecture')
                ->distinct()
                ->pluck('prefecture')
                ->sort()
                ->values();
        });

        // タグリスト（検索フォーム用・キャッシュ）
        $allTags = cache()->remember('model_tags', 3600, function () {
            return ModelProfile::where('is_public', true)
                ->whereNotNull('style_tags')
                ->get()
                ->pluck('style_tags')
                ->flatten()
                ->unique()
                ->sort()
                ->values();
        });

        // 体型リスト（検索フォーム用・キャッシュ）
        $bodyTypes = cache()->remember('model_body_types', 3600, function () {
            return ModelProfile::where('is_public', true)
                ->whereNotNull('body_type')
                ->distinct()
                ->pluck('body_type')
                ->filter()
                ->sort()
                ->values();
        });

        return view('models.index', compact('models', 'prefectures', 'allTags', 'bodyTypes'));
    }

    // 詳細
    public function show(ModelProfile $modelProfile, Request $request)
    {
        // 非公開プロフィールは404
        if (!$modelProfile->is_public) {
            abort(404);
        }

        // user も一緒に読み込む（念のため）
        $modelProfile->load('user', 'images');

        // タブの選択（デフォルトはprofile）
        $tab = $request->get('tab', 'profile');
        $validTabs = ['profile', 'qa', 'photo', 'comments'];
        if (!in_array($tab, $validTabs)) {
            $tab = 'profile';
        }

        // 受け取ったレビューを取得
        $reviews = \App\Models\Review::where('reviewed_user_id', $modelProfile->user_id)
            ->with(['reviewer', 'job'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Q&A（回答済みのみ公開表示）
        $questions = $modelProfile->questions()
            ->whereNotNull('answer')
            ->with('asker')
            ->orderBy('created_at', 'desc')
            ->get();

        // お気に入り状態といいね数を取得
        $isFavorite = false;
        $favoritesCount = $modelProfile->favorites()->count();
        if (Auth::check()) {
            $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', \App\Models\ModelProfile::class)
                ->where('favoritable_id', $modelProfile->id)
                ->exists();
        }

        return view('models.show', compact('modelProfile', 'reviews', 'questions', 'isFavorite', 'favoritesCount', 'tab'));
    }
}
