<x-guest-layout>
    <div class="page-narrow py-12 sm:py-16">
        <div class="max-w-md mx-auto">
            <div class="mb-8 text-center">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">
                    @if(isset($role) && $role === 'painter')
                        For Painter
                    @else
                        For Model
                    @endif
                </p>
                <h1 class="font-display text-2xl font-semibold text-secondary-900">新規登録</h1>
                <p class="text-sm text-secondary-500 mt-3 leading-relaxed">
                    まずはメールアドレスを入力してください。<br>
                    認証用のリンクをお送りします。
                </p>
            </div>

            @if(session('error'))
                <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-error-700 mb-1">Error</p>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.email.send') }}"
                  class="border border-secondary-200 bg-canvas-50 p-6 sm:p-8 space-y-5">
                @csrf
                <input type="hidden" name="role" value="{{ old('role', $role ?? 'model') }}">

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email" required autofocus autocomplete="email"
                           value="{{ old('email') }}"
                           placeholder="example@example.com"
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('email')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-secondary-200">
                    <a href="{{ route('login-register') }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                        ← Sign in
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
