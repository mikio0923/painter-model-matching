<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>Admin — {{ config('app.name', 'Palette') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-canvas-50 text-secondary-900">
        <div class="min-h-screen flex flex-col">

            {{-- 管理画面ナビゲーション（黒背景・美術館的） --}}
            <nav class="bg-secondary-900 text-canvas-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-14">
                        <div class="flex items-center gap-6">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                                <span class="font-display text-base font-semibold tracking-wide">{{ config('app.name', 'Palette') }}</span>
                                <span class="text-[10px] text-secondary-500 border border-secondary-700 px-1.5 py-0.5">管理画面</span>
                            </a>

                            <div class="hidden lg:flex items-center gap-0.5 text-sm">
                                @php
                                    $navItems = [
                                        ['href' => route('admin.dashboard'),                       'label' => 'ダッシュボード', 'active' => request()->routeIs('admin.dashboard')],
                                        ['href' => route('admin.users.index'),                     'label' => 'ユーザー',     'active' => request()->routeIs('admin.users.*')],
                                        ['href' => route('admin.jobs.index'),                      'label' => '依頼',         'active' => request()->routeIs('admin.jobs.*')],
                                        // 本人確認は一旦停止
                                        // ['href' => route('admin.identity-verifications.index'),    'label' => '本人確認',     'active' => request()->routeIs('admin.identity-verifications.*'), 'badge' => $adminNavBadges['identity'] ?? 0, 'badgeColor' => 'warning'],
                                        ['href' => route('admin.contacts.index'),                  'label' => 'お問い合わせ', 'active' => request()->routeIs('admin.contacts.*'), 'badge' => $adminNavBadges['contacts'] ?? 0, 'badgeColor' => 'error'],
                                        ['href' => route('admin.information.index'),               'label' => 'お知らせ',     'active' => request()->routeIs('admin.information.*')],
                                    ];
                                @endphp
                                @foreach($navItems as $item)
                                    <a href="{{ $item['href'] }}"
                                       class="relative px-3 py-2 text-sm transition-colors duration-200 inline-flex items-center gap-2
                                              {{ $item['active'] ? 'text-canvas-50 font-medium' : 'text-secondary-400 hover:text-canvas-50' }}">
                                        {{ $item['label'] }}
                                        @if(!empty($item['badge']) && $item['badge'] > 0)
                                            <span class="inline-flex items-center justify-center min-w-[16px] h-3.5 px-1 bg-canvas-50 text-secondary-900 text-[9px] font-medium">
                                                {{ $item['badge'] > 99 ? '99+' : $item['badge'] }}
                                            </span>
                                        @endif
                                        @if($item['active'])
                                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-px bg-canvas-50"></span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('home') }}" class="text-xs text-secondary-400 hover:text-canvas-50 transition-colors">
                                ← サイトへ
                            </a>
                            <span class="hidden sm:inline-block w-px h-4 bg-secondary-700"></span>
                            <form method="POST" action="{{ route('admin.logout') }}" class="hidden sm:inline">
                                @csrf
                                <button type="submit" class="text-xs text-secondary-400 hover:text-canvas-50 transition-colors">
                                    ログアウト
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- モバイルナビ --}}
                    <div class="lg:hidden flex items-center gap-0.5 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach($navItems as $item)
                            <a href="{{ $item['href'] }}"
                               class="shrink-0 px-3 py-1.5 text-xs inline-flex items-center gap-1.5 transition-colors
                                      {{ $item['active'] ? 'text-canvas-50 border-b border-canvas-50 font-medium' : 'text-secondary-400 hover:text-canvas-50' }}">
                                {{ $item['label'] }}
                                @if(!empty($item['badge']) && $item['badge'] > 0)
                                    <span class="inline-flex items-center justify-center min-w-[14px] h-3 px-0.5 bg-canvas-50 text-secondary-900 text-[8px] font-medium">{{ $item['badge'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
                {{-- フラッシュメッセージ --}}
                @if(session('success'))
                    <div class="mb-6 border-l-2 border-success-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs text-success-700 mb-1 font-medium">成功</p>
                            {{ session('success') }}
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-secondary-400 hover:text-secondary-700 text-xs">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 border-l-2 border-error-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs text-error-700 mb-1 font-medium">エラー</p>
                            {{ session('error') }}
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-secondary-400 hover:text-secondary-700 text-xs">✕</button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-secondary-200 px-4 py-6 text-center">
                <p class="text-xs text-secondary-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Palette') }} 管理画面
                </p>
            </footer>
        </div>
    </body>
</html>
