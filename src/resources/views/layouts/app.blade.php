<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $appName = config('app.name', 'Palette');
            $pageTitle = trim($__env->yieldContent('title'));
            $pageDescription = trim($__env->yieldContent('description'));
            if ($pageDescription === '') {
                $pageDescription = '画家とモデルをつなぐアートマッチングプラットフォーム。ポートレート・人物画の制作に特化したクリエイター同士のマッチング。';
            }
            $fullTitle = $pageTitle ? $pageTitle . ' — ' . $appName : $appName;
            $ogImage = asset('images/art-bg/great-wave.jpg');
            $canonicalUrl = url()->current();
        @endphp

        <title>{{ $fullTitle }}</title>
        <meta name="description" content="{{ $pageDescription }}">
        <link rel="canonical" href="{{ $canonicalUrl }}">

        {{-- Open Graph --}}
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $appName }}">
        <meta property="og:title" content="{{ $fullTitle }}">
        <meta property="og:description" content="{{ $pageDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:locale" content="ja_JP">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $fullTitle }}">
        <meta name="twitter:description" content="{{ $pageDescription }}">
        <meta name="twitter:image" content="{{ $ogImage }}">

        @stack('meta')

        <!-- Fonts: Cormorant Garamond (display serif) + Noto Sans JP (body) + Inter (UI) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Noto+Sans+JP:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-canvas-50 text-secondary-900">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading (オプション) -->
            @isset($header)
                <header class="bg-white border-b border-secondary-200 shadow-sm">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{-- トースト通知 --}}
                @if(session('success'))
                    <x-toast type="success" :message="session('success')" />
                @endif
                @if(session('error'))
                    <x-toast type="error" :message="session('error')" />
                @endif
                @if(session('warning'))
                    <x-toast type="warning" :message="session('warning')" />
                @endif
                @if(session('info'))
                    <x-toast type="info" :message="session('info')" />
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-canvas-50 text-secondary-600 mt-auto border-t border-secondary-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-10 mb-10">
                        <div class="col-span-2 md:col-span-1">
                            <span class="font-display text-2xl font-semibold text-secondary-900 tracking-wide">{{ config('app.name', 'Palette') }}</span>
                            <p class="mt-4 text-xs leading-relaxed text-secondary-500">
                                画家とモデルをつなぐ<br>アートマッチングプラットフォーム
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-secondary-500 uppercase tracking-[0.3em] mb-4">Service</p>
                            <ul class="space-y-3 text-sm">
                                <li><a href="{{ route('models.index') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">モデル一覧</a></li>
                                <li><a href="{{ route('jobs.index') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">依頼一覧</a></li>
                                <li><a href="{{ route('guide.model') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">モデルガイド</a></li>
                                <li><a href="{{ route('guide.painter') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">画家ガイド</a></li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-[10px] text-secondary-500 uppercase tracking-[0.3em] mb-4">Support</p>
                            <ul class="space-y-3 text-sm">
                                <li><a href="{{ route('faq') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">よくある質問</a></li>
                                <li><a href="{{ route('contact.create') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">お問い合わせ</a></li>
                                <li><a href="{{ route('guideline') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">ガイドライン</a></li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-[10px] text-secondary-500 uppercase tracking-[0.3em] mb-4">Legal</p>
                            <ul class="space-y-3 text-sm">
                                <li><a href="{{ route('terms') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">利用規約</a></li>
                                <li><a href="{{ route('privacy') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">プライバシーポリシー</a></li>
                                <li><a href="{{ route('about') }}" class="text-secondary-700 hover:text-secondary-900 transition-colors">運営について</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-8 border-t border-secondary-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-[10px] text-secondary-400 tracking-[0.2em] uppercase">&copy; {{ date('Y') }} {{ config('app.name', 'Palette') }}. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
