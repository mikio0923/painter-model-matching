<x-guest-layout>
    <div class="page-narrow py-16">
        <div class="max-w-md mx-auto">
            <div class="mb-8 text-center">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Password Reset</p>
                <h1 class="font-display text-2xl font-semibold text-secondary-900">パスワードをお忘れの方へ</h1>
                <p class="text-sm text-secondary-500 mt-3 leading-relaxed">
                    登録されているメールアドレスを入力してください。再設定用のリンクをお送りします。
                </p>
            </div>

            @if (session('status'))
                <div class="bg-canvas-50 border-l-2 border-success-500 px-4 py-3 mb-6 text-sm text-secondary-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="border border-secondary-200 bg-canvas-50 p-6 sm:p-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('email')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-secondary-200">
                    <a href="{{ route('login') }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                        ← Back to Login
                    </a>
                    <button type="submit" class="px-6 py-3 bg-secondary-900 text-canvas-50 text-xs uppercase tracking-[0.25em] border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        Send Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
