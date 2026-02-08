<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\ModelProfileQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModelQuestionController extends Controller
{
    /**
     * モデルへの質問一覧を表示
     */
    public function index(): View
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;

        if (!$modelProfile) {
            return view('model.questions.index', [
                'questions' => collect(),
                'modelProfile' => null,
            ]);
        }

        $questions = ModelProfileQuestion::where('model_profile_id', $modelProfile->id)
            ->with('asker')
            ->latest()
            ->paginate(20);

        return view('model.questions.index', [
            'questions' => $questions,
            'modelProfile' => $modelProfile,
        ]);
    }

    /**
     * 質問に回答する
     */
    public function answer(Request $request, ModelProfileQuestion $modelProfileQuestion): RedirectResponse
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;

        if (!$modelProfile || $modelProfileQuestion->model_profile_id !== $modelProfile->id) {
            abort(403);
        }

        $request->validate([
            'answer' => ['required', 'string', 'max:2000'],
        ]);

        $modelProfileQuestion->update(['answer' => $request->answer]);

        return redirect()->route('model.questions.index')
            ->with('success', '回答を保存しました');
    }

    /**
     * 回答編集フォームを表示
     */
    public function edit(ModelProfileQuestion $modelProfileQuestion): View|RedirectResponse
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;

        if (!$modelProfile || $modelProfileQuestion->model_profile_id !== $modelProfile->id) {
            abort(403);
        }

        return view('model.questions.edit', [
            'question' => $modelProfileQuestion->load('asker'),
        ]);
    }
}
