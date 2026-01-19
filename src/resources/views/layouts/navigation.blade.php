<nav class="bg-white border-b border-gray-200">
    {{-- 上部メニューバー --}}
    <div class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-12">
                <div class="flex items-center space-x-4">
                    {{-- ロゴ（ホームへ戻る） --}}
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-80 transition-opacity mr-2 text-white">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="text-lg font-bold hidden sm:inline">{{ config('app.name', 'Painter Model') }}</span>
                    </a>
                    <div class="h-6 w-px bg-gray-600"></div>
                    <div class="flex items-center space-x-2 text-sm">
                        <a href="{{ route('about') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                            モデルタウンとは？
                        </a>
                    <a href="{{ route('guideline') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                        ご利用ガイドライン
                    </a>
                    <a href="{{ route('jobs.index') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                        ジョブの一覧
                    </a>
                    <a href="{{ route('models.index') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                        モデルの一覧
                    </a>
                    <a href="{{ route('faq') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                        Q&A
                    </a>
                    <a href="{{ route('guide.model') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                        モデルになるガイド
                    </a>
                    <a href="{{ route('guide.painter') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                        モデルを探すガイド
                    </a>
                    </div>
                </div>
                <div class="flex items-center space-x-2 text-sm">
                    @auth
                        <a href="{{ route('notifications.index') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 relative text-white">
                            通知
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full -mt-1 -mr-1">
                                    {{ $unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('favorites.index') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                            お気に入り
                        </a>
                        <a href="{{ route('mypage') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                            マイページ
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                                ログアウト
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login-register') }}" class="px-4 py-2 rounded hover:bg-gray-700 transition-colors border border-transparent hover:border-gray-600 text-white">
                            新規登録・ログイン
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- メインナビゲーション --}}
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-16">
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-secondary-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        ホーム
                    </a>
                    <a href="{{ route('models.index') }}" class="text-secondary-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        モデル一覧
                    </a>
                    <a href="{{ route('jobs.index') }}" class="text-secondary-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        依頼一覧
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
