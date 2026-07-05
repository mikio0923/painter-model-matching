@extends('layouts.app')

@section('title', 'ポートフォリオ管理')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">ポートフォリオ</span>
        </div>
        <p class="page-header-subtitle">Portfolio</p>
        <h1 class="page-header-title mt-2">ポートフォリオ管理</h1>
        <p class="text-secondary-500 text-sm mt-3">あなたのポートフォリオ画像を管理します（最大 10 枚）。</p>
    </div>
</div>

<div class="page-narrow space-y-6">

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-success-700 mb-1 font-medium">完了</p>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-error-700 mb-1 font-medium">エラー</p>
            {{ session('error') }}
        </div>
    @endif

    {{-- アップロードフォーム --}}
    <form action="{{ route('model.portfolio.store') }}" method="POST" enctype="multipart/form-data"
          class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        @csrf
        <h2 class="font-display text-base font-semibold text-secondary-900 mb-3">新しい画像を追加</h2>
        <p class="text-xs text-secondary-500 mb-4">複数選択可。JPEG / PNG / GIF、各最大 5MB。残り <span class="text-secondary-900 font-medium">{{ 10 - $modelProfile->images->count() }}</span> 枚まで追加できます。</p>

        <div class="flex items-center gap-3 flex-wrap">
            <input type="file" id="images" name="images[]"
                   accept="image/jpeg,image/png,image/jpg,image/gif"
                   multiple class="hidden"
                   onchange="document.getElementById('selected-count').textContent = this.files.length + ' 枚選択中'">
            <label for="images"
                   class="cursor-pointer inline-flex items-center px-5 py-2.5 border border-secondary-400 text-secondary-700 text-sm hover:bg-secondary-100 transition-colors duration-200">
                ファイルを選択
            </label>
            <span id="selected-count" class="text-xs text-secondary-500">未選択</span>
            <button type="submit"
                    class="ml-auto px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-200">
                アップロード
            </button>
        </div>
        @error('images')<p class="text-xs text-error-600 mt-3">{{ $message }}</p>@enderror
        @error('images.*')<p class="text-xs text-error-600 mt-3">{{ $message }}</p>@enderror
    </form>

    {{-- 既存のポートフォリオ --}}
    @if($modelProfile->images->isEmpty())
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-sm text-secondary-500">まだポートフォリオ画像がありません。</p>
        </div>
    @else
        <div class="space-y-4">
            <h2 class="font-display text-base font-semibold text-secondary-900">投稿済み画像（{{ $modelProfile->images->count() }} 枚）</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($modelProfile->images as $image)
                    <div class="border border-secondary-200 bg-canvas-50 p-2">
                        <div class="relative">
                            <img src="{{ Storage::url($image->image_path) }}"
                                 alt="ポートフォリオ画像"
                                 class="w-full aspect-square object-cover border border-secondary-200">
                            <div class="absolute top-2 right-2">
                                @if($image->is_main)
                                    <span class="bg-secondary-900 text-canvas-50 text-[9px] uppercase tracking-[0.2em] px-2 py-1">Main</span>
                                @else
                                    <form action="{{ route('model.portfolio.set-main', $image) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-canvas-50 border border-secondary-400 text-secondary-700 text-[9px] uppercase tracking-[0.2em] px-2 py-1 hover:bg-secondary-100">
                                            Set Main
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('model.portfolio.destroy', $image) }}" method="POST"
                              onsubmit="return confirm('この画像を削除しますか？');" class="mt-2 text-center">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full px-3 py-1.5 border border-error-500 text-error-600 text-xs hover:bg-error-50 transition-colors duration-200">
                                削除
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="border-t border-secondary-200 pt-6">
        <a href="{{ route('mypage') }}"
           class="inline-flex items-center gap-2 text-sm text-secondary-500 hover:text-secondary-900 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            マイページに戻る
        </a>
    </div>
</div>
@endsection
