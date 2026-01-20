<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>管理画面 - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-secondary-100">
            {{-- 管理画面ナビゲーション --}}
            <nav class="bg-secondary-800 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                                管理画面
                            </a>
                            <div class="h-6 w-px bg-secondary-600"></div>
                            <div class="flex items-center space-x-2 text-sm">
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                    ダッシュボード
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                    ユーザー管理
                                </a>
                                <a href="{{ route('admin.jobs.index') }}" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                    依頼管理
                                </a>
                                <a href="{{ route('admin.contacts.index') }}" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                    お問い合わせ
                                </a>
                                <a href="{{ route('admin.information.index') }}" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                    お知らせ管理
                                </a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('home') }}" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                サイトに戻る
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded hover:bg-secondary-700 transition-colors">
                                    ログアウト
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{-- フラッシュメッセージ --}}
                @if(session('success'))
                    <div class="mb-6 bg-success-100 border border-success-400 text-success-700 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <svg class="fill-current h-6 w-6 text-success-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-error-100 border border-error-400 text-error-700 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <svg class="fill-current h-6 w-6 text-error-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>

