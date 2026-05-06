<x-guest-layout>
    <div class="page-narrow py-16">
        <div class="max-w-md mx-auto">
            <div class="mb-8 text-center">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">New Password</p>
                <h1 class="font-display text-2xl font-semibold text-secondary-900">パスワードの再設定</h1>
                <p class="text-sm text-secondary-500 mt-3">新しいパスワードを設定してください。</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="border border-secondary-200 bg-canvas-50 p-6 sm:p-8 space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('email')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">New Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('password')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm
                                  focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900
                                  transition-colors duration-200">
                    @error('password_confirmation')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="pt-3 border-t border-secondary-200">
                    <button type="submit" class="w-full py-3 bg-secondary-900 text-canvas-50 text-xs uppercase tracking-[0.25em] border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
