<nav class="bg-canvas-50 border-b border-secondary-200 sticky top-0 z-50"
     x-data="{ mobileOpen: false }"
     style="transform: translateZ(0);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- ロゴ --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="w-8 h-8 border border-secondary-900 flex items-center justify-center transition-colors duration-300 group-hover:bg-secondary-900">
                    <svg class="w-3.5 h-3.5 text-secondary-900 group-hover:text-canvas-50 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2a2 2 0 012 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 017 7h1a1 1 0 010 2h-1v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1H2a1 1 0 010-2h1a7 7 0 017-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 012-2z"/>
                    </svg>
                </div>
                <span class="font-display text-xl font-semibold text-secondary-900 tracking-wide">
                    {{ config('app.name', 'Palette') }}
                </span>
            </a>

            {{-- デスクトップナビ（中央） --}}
            <div class="hidden md:flex items-center gap-0.5">
                @php
                    $navLinks = [
                        ['href' => route('home'),          'label' => 'ホーム',       'active' => request()->routeIs('home')],
                        ['href' => route('models.index'),  'label' => 'モデル一覧',   'active' => request()->routeIs('models.*')],
                        ['href' => route('jobs.index'),    'label' => '依頼一覧',     'active' => request()->routeIs('jobs.*')],
                        ['href' => route('about'),         'label' => 'サービスについて', 'active' => request()->routeIs('about')],
                        ['href' => route('faq'),           'label' => 'Q&A',          'active' => request()->routeIs('faq')],
                    ];
                @endphp
                @foreach($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="relative inline-flex items-center px-4 h-16 text-sm font-medium cursor-pointer transition-colors duration-200
                              {{ $link['active']
                                  ? 'text-secondary-900'
                                  : 'text-secondary-600 hover:text-secondary-900' }}">
                        {{ $link['label'] }}
                        @if($link['active'])
                            <span class="absolute bottom-3 left-1/2 -translate-x-1/2 w-6 h-px bg-secondary-900 pointer-events-none"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- 右側アクション --}}
            <div class="flex items-center gap-2 shrink-0">
                @auth
                    {{-- 管理者バッジ --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 border border-secondary-900 text-secondary-900 text-xs font-medium uppercase tracking-wider hover:bg-secondary-900 hover:text-canvas-50 transition-colors duration-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Admin
                        </a>
                    @endif

                    {{-- 通知 --}}
                    <a href="{{ route('notifications.index') }}"
                       class="relative p-2 text-secondary-500 hover:text-secondary-900 transition-colors duration-200"
                       title="通知">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="absolute top-0.5 right-0.5 min-w-[16px] h-4 px-1 bg-error-500 text-canvas-50 text-[10px] font-semibold rounded-full flex items-center justify-center">
                                {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </a>

                    {{-- お気に入り --}}
                    <a href="{{ route('favorites.index') }}"
                       class="p-2 text-secondary-500 hover:text-secondary-900 transition-colors duration-200"
                       title="お気に入り">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>

                    {{-- ユーザーメニュー --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 text-sm font-medium text-secondary-700 hover:text-secondary-900 transition-colors duration-200">
                            <div class="w-7 h-7 border border-secondary-300 flex items-center justify-center text-secondary-700 font-display text-sm">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="hidden lg:block max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-3 h-3 text-secondary-400 hidden lg:block transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute right-0 mt-3 w-56 bg-canvas-50 border border-secondary-300 py-1 z-50"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-secondary-200 mb-1">
                                <p class="text-sm font-medium text-secondary-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-secondary-500 mt-1 uppercase tracking-[0.2em]">
                                    {{ auth()->user()->isModel() ? 'Model' : (auth()->user()->isPainter() ? 'Painter' : 'Admin') }}
                                </p>
                            </div>
                            <a href="{{ route('mypage') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 hover:text-secondary-900 hover:bg-secondary-100 transition-colors">
                                <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                マイページ
                            </a>
                            <a href="{{ route('mypage') }}#account-settings"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-700 hover:text-secondary-900 hover:bg-secondary-100 transition-colors">
                                <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                アカウント設定
                            </a>
                            <div class="border-t border-secondary-200 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 transition-colors text-left">
                                        <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        ログアウト
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- 未ログイン --}}
                    <a href="{{ route('login-register') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-secondary-900 text-canvas-50 text-sm font-medium border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        <span class="hidden sm:inline">ログイン / 新規登録</span>
                        <span class="sm:hidden">ログイン</span>
                    </a>
                @endauth

                {{-- モバイルメニューボタン --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden p-2 text-secondary-700 hover:text-secondary-900 transition-colors">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- モバイルメニュー --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-secondary-200 bg-canvas-50"
         style="display: none;">
        <div class="px-4 py-4 space-y-0.5">
            <a href="{{ route('home') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">ホーム</a>
            <a href="{{ route('models.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">モデル一覧</a>
            <a href="{{ route('jobs.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">依頼一覧</a>
            <a href="{{ route('about') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">サービスについて</a>
            <a href="{{ route('guideline') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">ガイドライン</a>
            <a href="{{ route('faq') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">Q&A</a>
            <a href="{{ route('guide.model') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">モデルになるガイド</a>
            <a href="{{ route('guide.painter') }}" class="flex items-center px-3 py-3 text-sm font-medium text-secondary-700 hover:bg-secondary-100 transition-colors">モデルを探すガイド</a>
        </div>
        @guest
            <div class="px-4 pb-4">
                <a href="{{ route('login-register') }}" class="block w-full text-center py-3 bg-secondary-900 text-canvas-50 text-sm font-medium hover:opacity-90 transition-opacity">ログイン / 新規登録</a>
            </div>
        @endguest
    </div>
</nav>
