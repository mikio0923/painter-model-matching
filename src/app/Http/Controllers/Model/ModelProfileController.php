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

        // オンライン対応で検索
        if ($request->filled('online_available')) {
            $query->where('online_available', true);
        }

        // タグで検索（JSON配列内を検索）
        if ($request->filled('tag')) {
            $tag = $request->tag;
            $query->whereJsonContains('style_tags', $tag);
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

        $models = $query->paginate(12)->withQueryString();

        // 都道府県リスト（検索フォーム用）
        $prefectures = ModelProfile::where('is_public', true)
            ->whereNotNull('prefecture')
            ->distinct()
            ->pluck('prefecture')
            ->sort()
            ->values();

        // タグリスト（検索フォーム用）
        $allTags = ModelProfile::where('is_public', true)
            ->whereNotNull('style_tags')
            ->get()
            ->pluck('style_tags')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('models.index', compact('models', 'prefectures', 'allTags'));
    }

    // 詳細
    public function show(ModelProfile $modelProfile)
    {
        // 非公開プロフィールは404
        if (!$modelProfile->is_public) {
            abort(404);
        }

        // user も一緒に読み込む（念のため）
        $modelProfile->load('user', 'images');

        // 受け取ったレビューを取得
        $reviews = \App\Models\Review::where('reviewed_user_id', $modelProfile->user_id)
            ->with(['reviewer', 'job'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('models.show', compact('modelProfile', 'reviews'));
    }
}
