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

    <div class="page-narrow space-y-12">

        {{-- ========== ログインフォーム（コンパクト） ========== --}}
        <section class="max-w-md mx-auto w-full">
            <div class="mb-5 text-center">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Sign in</p>
                <h2 class="font-display text-xl font-semibold text-secondary-900">ログイン</h2>
            </div>

            <form method="POST" action="{{ route('login') }}" class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full px-3 py-2 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('email')<p class="text-xs text-error-600 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div class="flex items-end justify-between mb-1.5">
                        <label for="password" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500">Password</label>
                        <a href="{{ route('password.request') }}" class="text-[11px] text-secondary-500 hover:text-secondary-900 underline underline-offset-2 transition-colors">
                            お忘れですか?
                        </a>
                    </div>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                           class="w-full px-3 py-2 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('password')<p class="text-xs text-error-600 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">Account Type</p>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="model" {{ old('role', 'model') === 'model' ? 'checked' : '' }} class="peer sr-only">
                            <span class="block text-center py-2 border border-secondary-300 text-sm text-secondary-700 peer-checked:border-secondary-900 peer-checked:bg-secondary-900 peer-checked:text-canvas-50 transition-colors duration-200">
                                モデル
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="painter" {{ old('role') === 'painter' ? 'checked' : '' }} class="peer sr-only">
                            <span class="block text-center py-2 border border-secondary-300 text-sm text-secondary-700 peer-checked:border-secondary-900 peer-checked:bg-secondary-900 peer-checked:text-canvas-50 transition-colors duration-200">
                                画家
                            </span>
                        </label>
                    </div>
                    @error('role')<p class="text-xs text-error-600 mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-secondary-200">
                    <label class="flex items-center gap-1.5 text-[11px] text-secondary-600 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_me" class="w-3.5 h-3.5 border-secondary-400">
                        自動ログイン
                    </label>
                    <button type="submit" class="px-6 py-2 bg-secondary-900 text-canvas-50 text-sm border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        ログイン
                    </button>
                </div>
            </form>
        </section>

        {{-- ========== 新規会員登録（カラーカード） ========== --}}
        <section>
            <div class="mb-6 text-center">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">New Account</p>
                <h2 class="font-display text-2xl font-semibold text-secondary-900">新規登録</h2>
                <p class="text-sm text-secondary-500 mt-2">
                    お役割をお選びください。
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- モデル登録 --}}
                <a href="{{ route('register', ['role' => 'model']) }}"
                   class="group relative block overflow-hidden border-2 border-success-500 bg-success-50 p-6 sm:p-8 hover:bg-success-500 hover:text-canvas-50 transition-all duration-300">
                    <div class="absolute top-4 right-4 w-12 h-12 rounded-full bg-success-500 group-hover:bg-canvas-50 flex items-center justify-center transition-colors duration-300">
                        <svg class="w-6 h-6 text-canvas-50 group-hover:text-success-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] tracking-[0.3em] uppercase text-success-700 group-hover:text-canvas-50 mb-2 transition-colors duration-300">For Model</p>
                    <h3 class="font-display text-2xl font-semibold text-secondary-900 group-hover:text-canvas-50 mb-3 transition-colors duration-300">モデルとして登録</h3>
                    <p class="text-xs text-secondary-600 group-hover:text-canvas-50/90 leading-relaxed mb-5 transition-colors duration-300">
                        画家からの依頼を受け、ポートレートのモデルとして活動するためのアカウントです。
                    </p>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-success-600 text-canvas-50 group-hover:bg-canvas-50 group-hover:text-success-700 text-xs font-medium transition-colors duration-300">
                        登録を始める
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>

                {{-- 画家登録 --}}
                <a href="{{ route('register', ['role' => 'painter']) }}"
                   class="group relative block overflow-hidden border-2 border-primary-500 bg-primary-50 p-6 sm:p-8 hover:bg-primary-500 hover:text-canvas-50 transition-all duration-300">
                    <div class="absolute top-4 right-4 w-12 h-12 rounded-full bg-primary-500 group-hover:bg-canvas-50 flex items-center justify-center transition-colors duration-300">
                        <svg class="w-6 h-6 text-canvas-50 group-hover:text-primary-600 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <p class="text-[10px] tracking-[0.3em] uppercase text-primary-700 group-hover:text-canvas-50 mb-2 transition-colors duration-300">For Painter</p>
                    <h3 class="font-display text-2xl font-semibold text-secondary-900 group-hover:text-canvas-50 mb-3 transition-colors duration-300">画家として登録</h3>
                    <p class="text-xs text-secondary-600 group-hover:text-canvas-50/90 leading-relaxed mb-5 transition-colors duration-300">
                        モデルへ依頼を出し、人物画・ポートレート制作を行うためのアカウントです。
                    </p>
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-canvas-50 group-hover:bg-canvas-50 group-hover:text-primary-700 text-xs font-medium transition-colors duration-300">
                        登録を始める
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
            </div>
        </section>
    </div>
</x-guest-layout>
