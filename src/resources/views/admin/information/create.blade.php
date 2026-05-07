@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.information.index') }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Information
        </a>
        <span class="text-secondary-300">/</span>
        <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-700">New</span>
    </div>
    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">New Information</p>
    <h1 class="font-display text-2xl font-semibold text-secondary-900">お知らせ作成</h1>
</div>

@php
    $rowLabel = 'block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2';
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
        <label for="type" class="{{ $rowLabel }}">タイプ <span class="text-error-500">*</span></label>
        <select id="type" name="type" required class="{{ $input }} max-w-md">
            <option value="">選択してください</option>
            <option value="information"   {{ old('type') === 'information'   ? 'selected' : '' }}>お知らせ</option>
            <option value="press_release" {{ old('type') === 'press_release' ? 'selected' : '' }}>プレスリリース</option>
        </select>
        @error('type')<p class="text-xs text-error-600 mt-2">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="content" class="{{ $rowLabel }}">内容 <span class="text-error-500">*</span></label>
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
           class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
            Cancel
        </a>
        <button type="submit"
                class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
            Create
        </button>
    </div>
</form>
@endsection
