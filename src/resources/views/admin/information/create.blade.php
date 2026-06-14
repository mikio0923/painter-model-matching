@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.information.index') }}" class="text-sm text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            お知らせ一覧
        </a>
        <span class="text-secondary-300">/</span>
        <span class="text-sm text-secondary-700">新規作成</span>
    </div>
    <h1 class="font-display text-2xl font-semibold text-secondary-900">お知らせを作成</h1>
    <p class="text-sm text-secondary-500 mt-1">新しいお知らせまたはプレスリリースを作成します。</p>
</div>

@php
    $rowLabel = 'block text-sm font-medium text-secondary-700 mb-1.5';
    $input    = 'w-full px-4 py-3 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200';
@endphp

<form action="{{ route('admin.information.store') }}" method="POST"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-8 space-y-6">
    @csrf

    <div>
        <label for="title" class="{{ $rowLabel }}">タイトル <span class="text-error-500">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="{{ $input }}">
        @error('title')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="type" class="{{ $rowLabel }}">種別 <span class="text-error-500">*</span></label>
        <select id="type" name="type" required class="{{ $input }} max-w-md">
            <option value="">選択してください</option>
            <option value="information"   {{ old('type') === 'information'   ? 'selected' : '' }}>お知らせ</option>
            <option value="press_release" {{ old('type') === 'press_release' ? 'selected' : '' }}>プレスリリース</option>
        </select>
        @error('type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="content" class="{{ $rowLabel }}">本文 <span class="text-error-500">*</span></label>
        <textarea id="content" name="content" rows="10" required class="{{ $input }} resize-y leading-relaxed">{{ old('content') }}</textarea>
        @error('content')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="published_at" class="{{ $rowLabel }}">公開日</label>
        <input type="date" id="published_at" name="published_at" value="{{ old('published_at') }}" class="{{ $input }} max-w-xs">
        @error('published_at')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                   class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
            <span class="text-sm text-secondary-700">公開する</span>
        </label>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-secondary-200">
        <a href="{{ route('admin.information.index') }}"
           class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-sm hover:bg-secondary-100 transition-colors duration-200 text-center">
            キャンセル
        </a>
        <button type="submit"
                class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
            作成する
        </button>
    </div>
</form>
@endsection
