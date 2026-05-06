<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * 管理者ログインフォームを表示
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * 管理者ログイン処理
     * - 一般ユーザー（model/painter）は弾く
     * - role='admin' のみ受け付ける
     * - レート制限（5回/分）
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $this->ensureIsNotRateLimited($request);

        // role='admin' に限定して検索
        $user = \App\Models\User::where('email', $validated['email'])
            ->where('role', 'admin')
            ->first();

        // 認証失敗（または管理者でない）→ 一般ログインと区別がつかない曖昧なメッセージで返す
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => 'メールアドレスまたはパスワードが正しくありません。',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * 管理者ログアウト
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * レート制限チェック（5回/分・IP+email）
     */
    private function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ], 'ja'),
        ]);
    }

    private function throttleKey(Request $request): string
    {
        return 'admin-login|' . Str::transliterate(Str::lower((string) $request->input('email')) . '|' . $request->ip());
    }
}
