<?php

namespace App\Http\Controllers;

use App\Services\AccountDeletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccountDeletionController extends Controller
{
    public function __construct(private AccountDeletionService $service) {}

    /**
     * 退会フォーム表示
     */
    public function show(): View
    {
        $user = Auth::user();

        // 退会できない理由を事前チェック（表示用）
        $blockers = [];
        try {
            $this->service->validateCanDelete($user);
        } catch (ValidationException $e) {
            $blockers = $e->errors()['account'] ?? [];
        }

        return view('account.delete', [
            'user' => $user,
            'blockers' => $blockers,
        ]);
    }

    /**
     * 退会実行
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'password' => ['required', 'string'],
            'confirm' => ['required', 'accepted'],
            'reason' => ['nullable', 'string', 'max:255'],
            'feedback' => ['nullable', 'string', 'max:2000'],
        ], [
            'confirm.accepted' => '退会することに同意してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        // パスワード再確認
        if (!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'パスワードが正しくありません。',
            ]);
        }

        try {
            $this->service->requestDeletion(
                $user,
                $validated['reason'] ?? null,
                $validated['feedback'] ?? null,
            );
        } catch (ValidationException $e) {
            return redirect()->route('account.delete.show')
                ->withErrors($e->validator);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', '退会処理が完了しました。ご利用ありがとうございました。');
    }
}
