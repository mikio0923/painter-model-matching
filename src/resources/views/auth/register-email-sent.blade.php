<x-guest-layout>
    <div class="page-narrow py-12 sm:py-16">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-10">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Email Sent</p>
                <h1 class="font-display text-2xl font-semibold text-secondary-900">認証メールを送信しました</h1>
            </div>

            <div class="border border-secondary-200 bg-canvas-50 p-6 sm:p-10 text-center">
                <svg class="w-12 h-12 text-secondary-300 mx-auto mb-6" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>

                <p class="text-secondary-700 mb-2">以下のメールアドレス宛に認証メールをお送りしました。</p>
                <p class="font-display text-base text-secondary-900 mb-6 break-all">
                    {{ session('email') }}
                </p>

                <div class="border-t border-secondary-200 pt-6 mt-6">
                    <p class="text-sm text-secondary-600 leading-relaxed">
                        メール内のリンクをクリックして、登録を完了してください。<br>
                        メールが届かない場合は、迷惑メールフォルダもご確認ください。
                    </p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('login-register') }}" class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                    ← Back to Sign in
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
