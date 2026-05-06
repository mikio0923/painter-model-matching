{{--
    エラーページ共通レイアウト（美術館調）

    使い方:
        @extends('errors.layout')
        @section('code', '404')
        @section('label', 'Not Found')
        @section('title', 'ページが見つかりません')
        @section('message', '...')
--}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <title>@yield('code') @yield('label') — {{ config('app.name', 'Palette') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Noto+Sans+JP:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-canvas-50 text-secondary-900 min-h-screen flex flex-col">
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="max-w-md w-full text-center">
            {{-- 大きなコード --}}
            <p class="font-display text-secondary-200 leading-none select-none mb-2"
               style="font-size: clamp(7rem, 18vw, 12rem); font-weight: 400; letter-spacing: -0.05em;">
                @yield('code')
            </p>

            {{-- ラベル（英字） --}}
            <p class="text-[10px] tracking-[0.4em] uppercase text-secondary-500 mb-6">
                @yield('label')
            </p>

            {{-- 区切り --}}
            <div class="w-12 h-px bg-secondary-300 mx-auto mb-6"></div>

            {{-- タイトル --}}
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-secondary-900 mb-4">
                @yield('title')
            </h1>

            {{-- 説明 --}}
            <p class="text-sm text-secondary-600 leading-relaxed mb-10">
                @yield('message')
            </p>

            {{-- アクション --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-secondary-900 text-canvas-50 text-xs uppercase tracking-[0.25em] border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                    Home
                </a>
                <button type="button" onclick="history.length > 1 ? history.back() : location.href='/'"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.25em] hover:bg-secondary-100 transition-colors duration-200">
                    Back
                </button>
            </div>
        </div>
    </main>

    <footer class="px-4 py-6 text-center border-t border-secondary-200">
        <p class="text-[10px] text-secondary-400 tracking-[0.2em] uppercase">
            &copy; {{ date('Y') }} {{ config('app.name', 'Palette') }}
        </p>
    </footer>
</body>
</html>
