<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\ModelProfileQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * モデル自身の Q&A (FAQ) を管理する。
 * 旧仕様: 画家からの質問を asker_id 付きで受信
 * 新仕様: モデル本人が「質問」と「回答」をセットで作成・編集・削除する
 *         asker_id IS NULL のレコードがモデル自作 FAQ として公開対象になる
 */
class ModelQuestionController extends Controller
{
    /**
     * モデル本人の Q&A 一覧（管理画面）
     */
    public function index(): View
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;

        if (!$modelProfile) {
            return view('model.questions.index', [
                'questions'    => collect(),
                'modelProfile' => null,
            ]);
        }

        $questions = ModelProfileQuestion::where('model_profile_id', $modelProfile->id)
            ->whereNull('asker_id') // モデル自作のみ
            ->latest()
            ->paginate(20);

        return view('model.questions.index', [
            'questions'    => $questions,
            'modelProfile' => $modelProfile,
        ]);
    }

    /**
     * 新規 Q&A を作成
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;
        abort_unless($modelProfile, 403, 'プロフィール未作成のため Q&A を投稿できません。');

        $request->validate([
            'question' => ['required', 'string', 'max:1000'],
            'answer'   => ['required', 'string', 'max:2000'],
        ]);

        ModelProfileQuestion::create([
            'model_profile_id' => $modelProfile->id,
            'asker_id'         => null, // モデル自作
            'question'         => $request->input('question'),
            'answer'           => $request->input('answer'),
        ]);

        return redirect()->route('model.questions.index')
            ->with('success', 'Q&A を追加しました。');
    }

    /**
     * 編集フォーム
     */
    public function edit(ModelProfileQuestion $modelProfileQuestion): View
    {
        $this->authorizeQuestion($modelProfileQuestion);

        return view('model.questions.edit', [
            'question' => $modelProfileQuestion,
        ]);
    }

    /**
     * 更新
     */
    public function update(Request $request, ModelProfileQuestion $modelProfileQuestion): RedirectResponse
    {
        $this->authorizeQuestion($modelProfileQuestion);

        $request->validate([
            'question' => ['required', 'string', 'max:1000'],
            'answer'   => ['required', 'string', 'max:2000'],
        ]);

        $modelProfileQuestion->update([
            'question' => $request->input('question'),
            'answer'   => $request->input('answer'),
        ]);

        return redirect()->route('model.questions.index')
            ->with('success', 'Q&A を更新しました。');
    }

    /**
     * 削除
     */
    public function destroy(ModelProfileQuestion $modelProfileQuestion): RedirectResponse
    {
        $this->authorizeQuestion($modelProfileQuestion);

        $modelProfileQuestion->delete();

        return redirect()->route('model.questions.index')
            ->with('success', 'Q&A を削除しました。');
    }

    private function authorizeQuestion(ModelProfileQuestion $question): void
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;
        abort_unless(
            $modelProfile
                && $question->model_profile_id === $modelProfile->id
                && $question->asker_id === null,
            403
        );
    }
}
