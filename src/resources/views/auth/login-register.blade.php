<x-guest-layout>
    {{-- ページヘッダー（背景アート） --}}
    <div class="page-header">
        <div class="art-bg-stage">
            <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
            <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
            <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
            <div class="art-bg-veil"></div>
        </div>
        <div class="page-header-inner text-center">
            <p class="page-header-subtitle">Sign in / Register</p>
            <h1 class="page-header-title mt-2">ようこそ</h1>
            <p class="text-secondary-500 text-sm mt-3">既にアカウントをお持ちの方はログイン、初めての方は登録へ。</p>
        </div>
    </div>

    <div class="page-narrow space-y-16">

        {{-- ========== ログインフォーム ========== --}}
        <section>
            <div class="mb-8">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Sign in</p>
                <h2 class="font-display text-2xl font-semibold text-secondary-900">ログイン</h2>
            </div>

            <form method="POST" action="{{ route('login') }}" class="border border-secondary-200 bg-canvas-50 p-6 sm:p-8 space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('email')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">Password</label>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('password')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                    <div class="mt-2">
                        <a href="{{ route('password.request') }}" class="text-xs text-secondary-500 hover:text-secondary-900 underline underline-offset-2 transition-colors">
                            パスワードをお忘れですか?
                        </a>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-3">Account Type</p>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="model" {{ old('role', 'model') === 'model' ? 'checked' : '' }} class="peer sr-only">
                            <span class="block text-center py-3 border border-secondary-300 text-sm text-secondary-700 peer-checked:border-secondary-900 peer-checked:bg-secondary-900 peer-checked:text-canvas-50 transition-colors duration-200">
                                Model<span class="block text-[9px] tracking-[0.2em] uppercase text-secondary-500 peer-checked:text-secondary-300">モデル</span>
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="painter" {{ old('role') === 'painter' ? 'checked' : '' }} class="peer sr-only">
                            <span class="block text-center py-3 border border-secondary-300 text-sm text-secondary-700 peer-checked:border-secondary-900 peer-checked:bg-secondary-900 peer-checked:text-canvas-50 transition-colors duration-200">
                                Painter<span class="block text-[9px] tracking-[0.2em] uppercase text-secondary-500 peer-checked:text-secondary-300">画家</span>
                            </span>
                        </label>
                    </div>
                    @error('role')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                    <p class="text-xs text-secondary-500 mt-3 leading-relaxed">
                        同じメールアドレスでモデル・画家の両方をお持ちの場合は、どちらでログインするかをお選びください。
                    </p>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-secondary-200">
                    <label class="flex items-center gap-2 text-xs text-secondary-600 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_me" class="w-3.5 h-3.5 border-secondary-400">
                        次回から自動的にログイン
                    </label>
                    <button type="submit" class="px-8 py-3 bg-secondary-900 text-canvas-50 text-xs uppercase tracking-[0.25em] border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        Sign in
                    </button>
                </div>
            </form>
        </section>

        {{-- ========== 新規会員登録 ========== --}}
        <section>
            <div class="mb-8">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">New Account</p>
                <h2 class="font-display text-2xl font-semibold text-secondary-900">新規登録</h2>
                <p class="text-sm text-secondary-500 mt-3">
                    {{ config('app.name', 'Palette') }} のご利用には会員登録（無料）が必要です。お役割をお選びください。
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 border-t border-l border-secondary-200">
                {{-- モデル登録 --}}
                <a href="{{ route('register', ['role' => 'model']) }}"
                   class="group block px-6 py-10 border-r border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300 relative">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">For Model</p>
                    <h3 class="font-display text-2xl font-semibold text-secondary-900 mb-3">モデルとして登録</h3>
                    <p class="text-xs text-secondary-500 leading-relaxed">
                        画家からの依頼を受け、ポートレートのモデルとして活動するためのアカウントです。
                    </p>
                    <div class="mt-6 inline-flex items-center gap-2 text-[10px] uppercase tracking-[0.25em] text-secondary-700 group-hover:text-secondary-900 transition-colors">
                        Sign up
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                {{-- 画家登録 --}}
                <a href="{{ route('register', ['role' => 'painter']) }}"
                   class="group block px-6 py-10 border-r border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300 relative">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">For Painter</p>
                    <h3 class="font-display text-2xl font-semibold text-secondary-900 mb-3">画家として登録</h3>
                    <p class="text-xs text-secondary-500 leading-relaxed">
                        モデルへ依頼を出し、人物画・ポートレート制作を行うためのアカウントです。
                    </p>
                    <div class="mt-6 inline-flex items-center gap-2 text-[10px] uppercase tracking-[0.25em] text-secondary-700 group-hover:text-secondary-900 transition-colors">
                        Sign up
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            </div>
        </section>
    </div>
</x-guest-layout>
