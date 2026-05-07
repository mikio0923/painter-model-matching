<x-guest-layout>
    <div class="page-narrow py-12 sm:py-16">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-10">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Verify Email</p>
                <h1 class="font-display text-2xl font-semibold text-secondary-900">メールアドレスをご確認ください</h1>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Sent</p>
                    認証リンクを再送信しました。
                </div>
            @endif

            <div class="border border-secondary-200 bg-canvas-50 p-6 sm:p-8 text-center">
                <svg class="w-12 h-12 text-secondary-300 mx-auto mb-6" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>

                <p class="text-sm text-secondary-600 leading-relaxed mb-6">
                    ご登録ありがとうございます。<br>
                    お送りした認証メール内のリンクをクリックして、メールアドレスの確認を完了してください。
                </p>
                <p class="text-xs text-secondary-500">届いていない場合は、下の「Resend」ボタンから再送可能です。</p>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                        Log out
                    </button>
                </form>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                            class="px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        Resend Email
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
