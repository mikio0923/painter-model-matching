@php
    $rowLabel = 'block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2';
    $input    = 'w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200';
    $user     = auth()->user();
@endphp

<section id="account-settings" class="space-y-8">
    <div class="border-b border-secondary-200 pb-3">
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Account Settings</p>
        <h2 class="font-display text-xl font-semibold text-secondary-900">アカウント設定</h2>
        <p class="text-secondary-500 text-sm mt-2">ログイン情報の変更や退会手続きはこちらから。</p>
    </div>

    {{-- 基本情報（名前・メール） --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        <div class="mb-5">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Profile Info</p>
            <h3 class="font-display text-base font-semibold text-secondary-900 mt-1">基本情報</h3>
            <p class="text-xs text-secondary-500 mt-1">名前とメールアドレスを変更できます。メールを変更した場合は再認証が必要です。</p>
        </div>

        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label for="account_name" class="{{ $rowLabel }}">名前</label>
                <input type="text" id="account_name" name="name"
                       value="{{ old('name', $user->name) }}" required autocomplete="name"
                       class="{{ $input }}">
                @error('name')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="account_email" class="{{ $rowLabel }}">メールアドレス</label>
                <input type="email" id="account_email" name="email"
                       value="{{ old('email', $user->email) }}" required autocomplete="username"
                       class="{{ $input }}">
                @error('email')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 border-l-2 border-warning-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-warning-700 mb-1">未認証</p>
                        メールアドレスがまだ認証されていません。
                        <button form="send-verification" class="underline ml-1 hover:text-secondary-900">
                            認証メールを再送する
                        </button>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-xs text-success-700">認証メールを送信しました。</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-3 border-t border-secondary-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-line-500 text-canvas-50 border border-line-500 text-sm hover:bg-line-600 hover:border-line-600 transition-colors duration-200">
                    保存する
                </button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                       class="text-xs text-success-700">保存しました</p>
                @endif
            </div>
        </form>
    </div>

    {{-- パスワード変更 --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        <div class="mb-5">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Password</p>
            <h3 class="font-display text-base font-semibold text-secondary-900 mt-1">パスワード変更</h3>
            <p class="text-xs text-secondary-500 mt-1">十分な長さのランダムなパスワードでアカウントを安全に保ちましょう。</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="update_password_current_password" class="{{ $rowLabel }}">現在のパスワード</label>
                <input type="password" id="update_password_current_password" name="current_password"
                       autocomplete="current-password" class="{{ $input }}">
                @if($errors->updatePassword->get('current_password'))
                    <p class="text-xs text-error-600 mt-2">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label for="update_password_password" class="{{ $rowLabel }}">新しいパスワード</label>
                <input type="password" id="update_password_password" name="password"
                       autocomplete="new-password" class="{{ $input }}">
                @if($errors->updatePassword->get('password'))
                    <p class="text-xs text-error-600 mt-2">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="update_password_password_confirmation" class="{{ $rowLabel }}">新しいパスワード（確認）</label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                       autocomplete="new-password" class="{{ $input }}">
                @if($errors->updatePassword->get('password_confirmation'))
                    <p class="text-xs text-error-600 mt-2">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-3 border-t border-secondary-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-line-500 text-canvas-50 border border-line-500 text-sm hover:bg-line-600 hover:border-line-600 transition-colors duration-200">
                    パスワードを更新
                </button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                       class="text-xs text-success-700">更新しました</p>
                @endif
            </div>
        </form>
    </div>

    {{-- メール配信設定 --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        <div class="mb-4 flex items-end justify-between gap-4 flex-wrap">
            <div>
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Email Preferences</p>
                <h3 class="font-display text-base font-semibold text-secondary-900 mt-1">メール配信設定</h3>
                <p class="text-xs text-secondary-500 mt-1">受け取りたいメールの種類を選べます。</p>
            </div>
            <a href="{{ route('account.email-preferences.edit') }}"
               class="px-5 py-2 border border-secondary-400 text-secondary-700 text-sm hover:bg-secondary-100 transition-colors duration-200">
                配信設定を開く
            </a>
        </div>
    </div>

    {{-- 退会 --}}
    <div class="border border-error-200 bg-canvas-50 p-5 sm:p-6">
        <div class="mb-4">
            <p class="text-[10px] tracking-[0.3em] uppercase text-error-600">Danger Zone</p>
            <h3 class="font-display text-base font-semibold text-secondary-900 mt-1">アカウント退会</h3>
            <p class="text-xs text-secondary-500 mt-2 leading-relaxed">
                退会するとアカウントは利用停止となり、{{ \App\Services\AccountDeletionService::GRACE_PERIOD_DAYS }} 日後に完全に削除されます。<br>
                取引中のメッセージや投稿、応募情報も合わせて失われますのでご注意ください。
            </p>
        </div>
        <a href="{{ route('account.delete.show') }}"
           class="inline-block px-6 py-2.5 border border-error-500 text-error-600 text-sm hover:bg-error-50 transition-colors duration-200">
            退会手続きへ進む
        </a>
    </div>
</section>
