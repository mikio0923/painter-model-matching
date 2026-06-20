import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Noto Sans JP"', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Cormorant Garamond"', '"Noto Serif JP"', 'Georgia', 'serif'],
            },
            colors: {
                // プライマリ（ディープバイオレット — 創造性・上質さ）
                primary: {
                    50:  '#f5f3ff',
                    100: '#ede9fe',
                    200: '#ddd6fe',
                    300: '#c4b5fd',
                    400: '#a78bfa',
                    500: '#8b5cf6',
                    600: '#7c3aed',  // メイン
                    700: '#6d28d9',
                    800: '#5b21b6',
                    900: '#3b0764',
                },
                // アクセント（ウォームクリムゾン — 情熱・芸術性）
                accent: {
                    50:  '#fff1f2',
                    100: '#ffe4e6',
                    200: '#fecdd3',
                    300: '#fda4af',
                    400: '#fb7185',
                    500: '#f43f5e',  // メイン
                    600: '#e11d48',
                    700: '#be123c',
                    800: '#9f1239',
                    900: '#881337',
                },
                // ゴールド（プレミアム・フィーチャー）
                gold: {
                    50:  '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',  // メイン
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                // キャンバス（温かみのある背景 — 紙・パーチメント）
                canvas: {
                    50:  '#fdfaf6',
                    100: '#faf4ec',
                    200: '#f3e6d5',
                    300: '#e8cfb2',
                    400: '#dab68e',
                    500: '#cc9d72',
                    600: '#b67d50',
                    700: '#976040',
                    800: '#784b35',
                    900: '#5a3728',
                },
                // セカンダリ（ウォームストーン）
                secondary: {
                    50:  '#fafaf9',
                    100: '#f5f5f4',
                    200: '#e7e5e4',
                    300: '#d6d3d1',
                    400: '#a8a29e',
                    500: '#78716c',
                    600: '#57534e',
                    700: '#44403c',
                    800: '#292524',
                    900: '#1c1917',
                },
                // 成功（グリーン）
                success: {
                    50:  '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
                // LINE グリーン
                line: {
                    50:  '#e6f9ee',
                    400: '#3CD97A',
                    500: '#06C755',  // メイン
                    600: '#05A748',
                    700: '#048438',
                },
                // 警告（アンバー）
                warning: {
                    50:  '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                // エラー（レッド）
                error: {
                    50:  '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
            },
            fontSize: {
                'xs':   ['0.75rem',  { lineHeight: '1.5' }],
                'sm':   ['0.875rem', { lineHeight: '1.5' }],
                'base': ['1rem',     { lineHeight: '1.75' }],
                'lg':   ['1.125rem', { lineHeight: '1.75' }],
                'xl':   ['1.25rem',  { lineHeight: '1.6' }],
                '2xl':  ['1.5rem',   { lineHeight: '1.4' }],
                '3xl':  ['1.875rem', { lineHeight: '1.3' }],
                '4xl':  ['2.25rem',  { lineHeight: '1.2' }],
                '5xl':  ['3rem',     { lineHeight: '1.1' }],
                '6xl':  ['3.75rem',  { lineHeight: '1.05' }],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            borderRadius: {
                'xl':  '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'soft':   '0 2px 15px -3px rgba(0,0,0,0.07), 0 10px 20px -2px rgba(0,0,0,0.04)',
                'glow':   '0 0 20px rgba(124,58,237,0.25)',
                'glow-accent': '0 0 20px rgba(225,29,72,0.25)',
                'card':   '0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.06)',
                'card-hover': '0 4px 6px rgba(0,0,0,0.04), 0 12px 28px rgba(0,0,0,0.1)',
            },
            backgroundImage: {
                'hero-gradient': 'linear-gradient(135deg, #1e0a3c 0%, #3b0764 30%, #6d28d9 65%, #be123c 100%)',
                'hero-gradient-subtle': 'linear-gradient(160deg, #1c1917 0%, #2d1460 50%, #3b0764 100%)',
                'card-gradient': 'linear-gradient(180deg, transparent 50%, rgba(28,25,23,0.85) 100%)',
                'gold-gradient': 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            },
            animation: {
                'fade-in': 'fadeIn 0.4s ease-out',
                'slide-up': 'slideUp 0.4s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            transitionTimingFunction: {
                'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
        },
    },

    plugins: [forms],
};
