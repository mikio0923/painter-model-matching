<x-guest-layout>
    <div class="page-narrow py-12 sm:py-16">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-10">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Confirm Password</p>
                <h1 class="font-display text-2xl font-semibold text-secondary-900">パスワード再確認</h1>
                <p class="text-sm text-secondary-500 mt-3 leading-relaxed">
                    セキュリティのため、続行する前にもう一度パスワードを入力してください。
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}"
                  class="border border-secondary-200 bg-canvas-50 p-6 sm:p-8 space-y-5">
                @csrf

                <div>
                    <label for="password" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" autofocus
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('password')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end pt-3 border-t border-secondary-200">
                    <button type="submit"
                            class="px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
