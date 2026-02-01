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

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <p class="text-secondary-700 mb-6">
                    会員登録を開始するには、メールアドレスを入力してください。<br>
                    入力されたメールアドレスに認証リンクを送信します。
                </p>

                <form method="POST" action="{{ route('register.email.send') }}" class="space-y-4">
                    @csrf

                    <!-- Role (hidden, set from URL parameter) -->
                    <input type="hidden" name="role" value="{{ old('role', $role ?? 'model') }}">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            メールアドレス <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="email"
                               placeholder="example@example.com"
                               class="form-input">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a class="link-secondary text-sm" href="{{ route('login-register') }}">
                            ログインはこちら
                        </a>

                        <button type="submit" class="ml-4 btn-primary">
                            認証メールを送信
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
