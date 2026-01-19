<x-guest-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h2 class="text-xl font-bold mb-6">
            > 新規会員登録
            @if(isset($role) && $role === 'model')
                (モデルの方)
            @elseif(isset($role) && $role === 'painter')
                (クライアントの方)
            @endif
        </h2>

        <form method="POST" action="{{ route('register') }}" class="card">
            <div class="card-body">
            @csrf

            <!-- Role (hidden, set from URL parameter) -->
            <input type="hidden" name="role" value="{{ old('role', $role ?? 'model') }}">

            <table class="w-full mb-4">
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700 w-1/3">お名前</td>
                    <td class="py-3">
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               class="form-input">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700">メールアドレス</td>
                    <td class="py-3">
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username"
                               class="form-input">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700">パスワード</td>
                    <td class="py-3">
                        <input type="password" 
                               name="password" 
                               id="password"
                               required 
                               autocomplete="new-password"
                               class="form-input">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr class="border-b border-secondary-200">
                    <td class="py-3 pr-4 text-sm font-medium text-secondary-700">パスワード（確認）</td>
                    <td class="py-3">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation"
                               required 
                               autocomplete="new-password"
                               class="form-input">
                        @error('password_confirmation')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
            </table>

            <div class="flex items-center justify-end mt-6">
                <a class="link-secondary text-sm" href="{{ route('login-register') }}">
                    ログインはこちら
                </a>

                <button type="submit" class="ml-4 btn-primary">
                    登録する
                </button>
            </div>
            </div>
        </form>
    </div>
</x-guest-layout>
