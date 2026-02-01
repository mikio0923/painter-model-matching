<x-guest-layout>
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h2 class="text-xl font-bold mb-6">> メール送信完了</h2>

        <div class="card">
            <div class="card-body">
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">
                        認証メールを送信しました
                    </h3>
                    <p class="text-secondary-700 mb-2">
                        <strong>{{ session('email') }}</strong> に認証メールを送信しました。
                    </p>
                    <p class="text-sm text-secondary-600 mb-6">
                        メールに記載されているリンクをクリックして、会員登録を完了してください。<br>
                        メールが届かない場合は、迷惑メールフォルダもご確認ください。
                    </p>
                    <div class="space-y-2">
                        <a href="{{ route('login-register') }}" class="link-primary text-sm">
                            ログインページに戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
