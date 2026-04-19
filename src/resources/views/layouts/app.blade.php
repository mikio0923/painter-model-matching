<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Palette') }}</title>

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
            <footer class="bg-secondary-900 text-secondary-400 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                        <div class="col-span-2 md:col-span-1">
                            <span class="font-display text-xl font-bold text-white">{{ config('app.name', 'Palette') }}</span>
                            <p class="mt-3 text-xs leading-relaxed text-secondary-500">
                                画家とモデルをつなぐ<br>アートマッチングプラットフォーム
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-secondary-300 uppercase tracking-wider mb-3">サービス</p>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('models.index') }}" class="hover:text-white transition-colors">モデル一覧</a></li>
                                <li><a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">依頼一覧</a></li>
                                <li><a href="{{ route('guide.model') }}" class="hover:text-white transition-colors">モデルガイド</a></li>
                                <li><a href="{{ route('guide.painter') }}" class="hover:text-white transition-colors">画家ガイド</a></li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-secondary-300 uppercase tracking-wider mb-3">サポート</p>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors">よくある質問</a></li>
                                <li><a href="{{ route('contact.create') }}" class="hover:text-white transition-colors">お問い合わせ</a></li>
                                <li><a href="{{ route('guideline') }}" class="hover:text-white transition-colors">ガイドライン</a></li>
                            </ul>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-secondary-300 uppercase tracking-wider mb-3">法的情報</p>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">利用規約</a></li>
                                <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">プライバシーポリシー</a></li>
                                <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">運営について</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="pt-8 border-t border-secondary-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-secondary-600">&copy; {{ date('Y') }} {{ config('app.name', 'Palette') }}. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
