<?php

namespace App\Http\Controllers;

use App\Models\ModelProfile;
use App\Models\ModelProfileQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelProfileQuestionController extends Controller
{
    /**
     * 画家がモデルに質問を投稿する
     */
    public function store(Request $request, ModelProfile $modelProfile): RedirectResponse
    {
        $request->validate([
            'question' => ['required', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        // 画家のみ質問可能（モデル自身への自己質問は不可）
        if ($user->role !== 'painter') {
            abort(403, '画家のみ質問できます');
        }

        if ($modelProfile->user_id === $user->id) {
            abort(403, '自分への質問はできません');
        }

        if (!$modelProfile->is_public) {
            abort(404);
        }

        ModelProfileQuestion::create([
            'model_profile_id' => $modelProfile->id,
            'asker_id' => $user->id,
            'question' => $request->question,
        ]);

        return redirect()->back()
            ->with('success', '質問を送信しました');
    }
}
