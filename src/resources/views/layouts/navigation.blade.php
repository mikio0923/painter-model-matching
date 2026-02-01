<nav class="bg-white border-b border-gray-200">
    {{-- 上部メニューバー --}}
    <div class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            <div class="flex items-center justify-between min-h-12 py-1.5 gap-2">
                <div class="flex items-center gap-2 min-w-0 flex-1 overflow-hidden">
                    {{-- ロゴ（ホームへ戻る） --}}
                    <a href="{{ route('home') }}" class="flex items-center space-x-1 hover:opacity-80 transition-opacity text-white shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="text-sm sm:text-base font-bold hidden sm:inline">{{ config('app.name', 'Painter Model') }}</span>
                    </a>
                    <div class="h-5 w-px bg-gray-600 shrink-0 hidden sm:block"></div>
                    {{-- メニューリンク（スクロール可能） --}}
                    <div class="flex items-center gap-0.5 sm:gap-1 text-[9px] sm:text-[10px] md:text-xs flex-nowrap whitespace-nowrap min-w-0 overflow-x-auto scrollbar-hide flex-1">
                        <a href="{{ route('about') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0" title="モデルタウンとは？">
                            <span class="hidden sm:inline">モデルタウンとは？</span>
                            <span class="sm:hidden">とは？</span>
                        </a>
                        <a href="{{ route('guideline') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0" title="ご利用ガイドライン">
                            <span class="hidden md:inline">ご利用ガイドライン</span>
                            <span class="md:hidden">ガイドライン</span>
                        </a>
                        <a href="{{ route('jobs.index') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0">
                            <span class="hidden sm:inline">ジョブの一覧</span>
                            <span class="sm:hidden">ジョブ</span>
                        </a>
                        <a href="{{ route('models.index') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0">
                            <span class="hidden sm:inline">モデルの一覧</span>
                            <span class="sm:hidden">モデル</span>
                        </a>
                        <a href="{{ route('faq') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0">
                            Q&A
                        </a>
                        <a href="{{ route('guide.model') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0" title="モデルになるガイド">
                            <span class="hidden lg:inline">モデルになるガイド</span>
                            <span class="lg:hidden hidden md:inline">モデルガイド</span>
                            <span class="md:hidden">モデル</span>
                        </a>
                        <a href="{{ route('guide.painter') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0" title="モデルを探すガイド">
                            <span class="hidden lg:inline">モデルを探すガイド</span>
                            <span class="lg:hidden hidden md:inline">探すガイド</span>
                            <span class="md:hidden">探す</span>
                        </a>
                    </div>
                </div>
                {{-- 右側メニュー（ログイン時） --}}
                <div class="flex items-center gap-0.5 sm:gap-1 text-[9px] sm:text-[10px] md:text-xs flex-nowrap whitespace-nowrap shrink-0">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white bg-red-600 shrink-0">
                                管理
                            </a>
                        @endif
                        <a href="{{ route('notifications.index') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors relative text-white shrink-0" title="通知">
                            <span class="hidden sm:inline">通知</span>
                            <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-[8px] font-semibold px-1 py-0 rounded-full -mt-0.5 -mr-0.5 min-w-[14px] text-center">
                                    {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('favorites.index') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0" title="お気に入り">
                            <span class="hidden sm:inline">お気に入り</span>
                            <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('mypage') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0" title="マイページ">
                            <span class="hidden sm:inline">マイページ</span>
                            <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline shrink-0">
                            @csrf
                            <button type="submit" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white" title="ログアウト">
                                <span class="hidden sm:inline">ログアウト</span>
                                <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login-register') }}" class="px-1.5 sm:px-2 py-0.5 sm:py-1 leading-tight rounded hover:bg-gray-700 transition-colors text-white shrink-0">
                            <span class="hidden sm:inline">新規登録・ログイン</span>
                            <span class="sm:hidden">ログイン</span>
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
