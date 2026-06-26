@extends('layouts.app')

@section('title', 'Q&A を編集')

@section('content')

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('model.questions.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Q &amp; A
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">編集</span>
        </div>
        <p class="page-header-subtitle">Edit Q&amp;A</p>
        <h1 class="page-header-title mt-2">Q&amp;A を編集</h1>
    </div>
</div>

<div class="page-narrow space-y-5">
    <form action="{{ route('model.questions.update', $question) }}" method="POST" class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label for="question" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">質問 <span class="text-error-500">*</span></label>
            <textarea id="question" name="question" rows="2" required maxlength="1000"
                      class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900">{{ old('question', $question->question) }}</textarea>
            @error('question')<p class="text-xs text-error-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="answer" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">回答 <span class="text-error-500">*</span></label>
            <textarea id="answer" name="answer" rows="6" required maxlength="2000"
                      class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900">{{ old('answer', $question->answer) }}</textarea>
            @error('answer')<p class="text-xs text-error-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-3 border-t border-secondary-200">
            <a href="{{ route('model.questions.index') }}"
               class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
                キャンセル
            </a>
            <button type="submit"
                    class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                保存
            </button>
        </div>
    </form>
</div>
@endsection
