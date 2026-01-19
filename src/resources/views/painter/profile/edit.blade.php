@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6">プロフィール編集</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('painter.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 表示名 --}}
        <div>
            <label for="display_name" class="block text-sm font-medium text-gray-700 mb-1">
                表示名 <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="display_name" 
                   name="display_name" 
                   value="{{ old('display_name', $painterProfile->display_name) }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('display_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- 都道府県 --}}
        <div>
            <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-1">
                都道府県
            </label>
            <input type="text" 
                   id="prefecture" 
                   name="prefecture" 
                   value="{{ old('prefecture', $painterProfile->prefecture) }}"
                   placeholder="例：東京都"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('prefecture')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- アートスタイル --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                アートスタイル（カンマ区切りで入力）
            </label>
            <input type="text" 
                   name="art_styles_input" 
                   value="{{ old('art_styles_input', is_array($painterProfile->art_styles) ? implode(',', $painterProfile->art_styles) : '') }}"
                   placeholder="例：油絵,デジタル,水彩画"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <p class="mt-1 text-sm text-gray-500">カンマ区切りで複数のスタイルを入力できます（例：油絵,デジタル,水彩画,アクリル画）</p>
            @error('art_styles')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- ポートフォリオURL --}}
        <div>
            <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-1">
                ポートフォリオURL
            </label>
            <input type="url" 
                   id="portfolio_url" 
                   name="portfolio_url" 
                   value="{{ old('portfolio_url', $painterProfile->portfolio_url) }}"
                   placeholder="https://example.com"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('portfolio_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">作品を公開しているWebサイトのURLを入力してください</p>
        </div>

        {{-- 送信ボタン --}}
        <div class="flex gap-4">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                更新する
            </button>
            <a href="{{ route('mypage') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection

