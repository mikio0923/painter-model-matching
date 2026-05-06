<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Palette') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Noto+Sans+JP:wght@400;500;600;700&family=Noto+Serif+JP:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-canvas-50 text-secondary-900">
        <div class="min-h-screen flex flex-col">

            {{-- ミニマルナビ（ロゴのみ） --}}
            <nav class="border-b border-secondary-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <a href="/" class="flex items-center gap-3 group shrink-0">
                        <div class="w-8 h-8 border border-secondary-900 flex items-center justify-center transition-colors duration-300 group-hover:bg-secondary-900">
                            <svg class="w-3.5 h-3.5 text-secondary-900 group-hover:text-canvas-50 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a2 2 0 012 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 017 7h1a1 1 0 010 2h-1v1a2 2 0 01-2 2H5a2 2 0 01-2-2v-1H2a1 1 0 010-2h1a7 7 0 017-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 012-2z"/>
                            </svg>
                        </div>
                        <span class="font-display text-xl font-semibold text-secondary-900 tracking-wide">
                            {{ config('app.name', 'Palette') }}
                        </span>
                    </a>
                    <a href="/" class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                        ← Back to site
                    </a>
                </div>
            </nav>

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="border-t border-secondary-200 px-4 py-6 text-center">
                <p class="text-[10px] text-secondary-400 tracking-[0.2em] uppercase">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Palette') }}
                </p>
            </footer>

        </div>
    </body>
</html>
