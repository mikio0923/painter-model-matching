<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login — {{ config('app.name', 'Palette') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Noto+Sans+JP:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-secondary-900 min-h-screen flex flex-col">
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-sm">
            {{-- ロゴ --}}
            <div class="text-center mb-12">
                <p class="text-[10px] tracking-[0.4em] uppercase text-secondary-500 mb-2">Administration</p>
                <h1 class="font-display text-3xl font-semibold text-canvas-50 tracking-wide">
                    {{ config('app.name', 'Palette') }}
                </h1>
                <div class="mt-4 inline-block px-3 py-0.5 border border-secondary-600 text-secondary-400 text-[10px] uppercase tracking-[0.3em]">
                    Restricted Area
                </div>
            </div>

            {{-- フォーム --}}
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-400 mb-2">
                        Email
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           class="w-full px-4 py-3 bg-secondary-800 border border-secondary-700 text-canvas-50 text-sm
                                  focus:outline-none focus:border-canvas-50 focus:ring-1 focus:ring-canvas-50
                                  transition-colors duration-200
                                  placeholder:text-secondary-600">
                </div>

                <div>
                    <label for="password" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-400 mb-2">
                        Password
                    </label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           class="w-full px-4 py-3 bg-secondary-800 border border-secondary-700 text-canvas-50 text-sm
                                  focus:outline-none focus:border-canvas-50 focus:ring-1 focus:ring-canvas-50
                                  transition-colors duration-200">
                </div>

                @if($errors->any())
                    <div class="bg-error-900/30 border border-error-800 text-error-300 text-xs p-3">
                        @foreach($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 text-xs text-secondary-400 select-none cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 bg-secondary-800 border-secondary-600 rounded-none">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 bg-canvas-50 text-secondary-900 text-xs font-medium uppercase tracking-[0.3em]
                               border border-canvas-50
                               hover:bg-transparent hover:text-canvas-50
                               transition-colors duration-300
                               focus:outline-none focus:ring-2 focus:ring-canvas-50 focus:ring-offset-2 focus:ring-offset-secondary-900">
                    Sign in
                </button>
            </form>

            <div class="mt-12 text-center">
                <a href="{{ url('/') }}" class="text-[10px] text-secondary-500 hover:text-secondary-300 tracking-[0.3em] uppercase transition-colors">
                    ← Back to site
                </a>
            </div>
        </div>
    </main>

    <footer class="px-4 py-6 text-center">
        <p class="text-[10px] text-secondary-600 tracking-[0.2em] uppercase">
            &copy; {{ date('Y') }} {{ config('app.name', 'Palette') }} · Admin
        </p>
    </footer>
</body>
</html>
