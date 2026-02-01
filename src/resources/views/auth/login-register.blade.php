<x-guest-layout>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        {{-- ログインフォーム --}}
        <section class="mb-12">
            <h2 class="section-subtitle">> LOGIN / ログインフォーム</h2>
            <p class="text-sm text-secondary-600 mb-6">
                メールアドレス・パスワードを入力して、「ログイン」ボタンをクリックして下さい。
            </p>

            <form method="POST" action="{{ route('login') }}" class="card">
                @csrf
                <div class="card-body">
                    <table class="w-full mb-4">
                        <tr class="border-b border-secondary-200">
                            <td class="py-3 pr-4 text-sm font-medium text-secondary-700 w-1/3">メールアドレス</td>
                            <td class="py-3">
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus 
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
                                       autocomplete="current-password"
                                       class="form-input">
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <div class="mt-2">
                                    <a href="{{ route('password.request') }}" class="link-primary text-sm">
                                        パスワードをお忘れですか?
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b border-secondary-200">
                            <td class="py-3 pr-4 text-sm font-medium text-secondary-700">ログイン種別</td>
                            <td class="py-3">
                                <div class="flex gap-4">
                                    <label class="flex items-center whitespace-nowrap">
                                        <input type="radio" 
                                               name="role" 
                                               value="model"
                                               {{ old('role', 'model') === 'model' ? 'checked' : '' }}
                                               class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-secondary-700 whitespace-nowrap">モデル</span>
                                    </label>
                                    <label class="flex items-center whitespace-nowrap">
                                        <input type="radio" 
                                               name="role" 
                                               value="painter"
                                               {{ old('role') === 'painter' ? 'checked' : '' }}
                                               class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-secondary-700 whitespace-nowrap">クライアント（画家）</span>
                                    </label>
                                </div>
                                @error('role')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-secondary-500 mt-2">
                                    同じメールアドレスでモデルとクライアントの両方のアカウントをお持ちの場合は、どちらでログインするか選択してください。
                                </p>
                            </td>
                        </tr>
                    </table>

                    <div class="flex items-center justify-end mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="remember" 
                                   id="remember_me"
                                   class="rounded border-secondary-300 text-primary-600 shadow-sm focus:ring-primary-500">
                            <span class="ml-2 text-sm text-secondary-600">次回から自動的にログイン</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-20 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 shadow-md hover:shadow-lg">
                            ログイン
                        </button>
                    </div>
                </div>
            </form>
        </section>

        {{-- 新規会員登録セクション --}}
        <section>
            <h2 class="section-subtitle">> 新規会員登録</h2>
            <p class="text-sm text-secondary-600 mb-6">
                ModelTownをご利用いただくには、会員登録(無料)が必要です。初めての方は、以下のページに進んで会員登録を行ってください。
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- モデルの新規登録 --}}
                <a href="{{ route('register', ['role' => 'model']) }}" 
                   class="block bg-accent-500 hover:bg-accent-600 text-white text-center py-8 px-6 rounded-xl transition-colors shadow-md hover:shadow-lg">
                    <div class="text-lg font-semibold mb-2">モデルの方の</div>
                    <div class="text-base">新規会員登録はこちら</div>
                </a>

                {{-- 画家の新規登録 --}}
                <a href="{{ route('register', ['role' => 'painter']) }}" 
                   class="block bg-secondary-700 hover:bg-secondary-800 text-white text-center py-8 px-6 rounded-xl transition-colors shadow-md hover:shadow-lg">
                    <div class="text-lg font-semibold mb-2">クライアントの方の</div>
                    <div class="text-base">新規会員登録はこちら</div>
                </a>
            </div>
        </section>
    </div>
</x-guest-layout>

