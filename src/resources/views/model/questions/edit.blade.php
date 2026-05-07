@extends('layouts.app')

@section('title', '回答を編集')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('model.questions.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Q & A
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">回答を編集</span>
        </div>
        <p class="page-header-subtitle">Edit Answer</p>
        <h1 class="page-header-title mt-2">回答を編集</h1>
    </div>
</div>

<div class="page-narrow space-y-5">

    {{-- 質問本文 --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        <div class="flex items-baseline gap-3 mb-3">
            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">
                {{ $question->asker->name ?? '退会済みユーザー' }}
            </p>
            <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                {{ $question->created_at->format('Y . n . j') }}
            </p>
        </div>
        <p class="text-secondary-900 whitespace-pre-wrap leading-relaxed">{{ $question->question }}</p>
    </div>

    {{-- 回答フォーム --}}
    <form action="{{ route('model.questions.answer', $question) }}" method="POST" class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 space-y-4">
        @csrf
        <div>
            <label for="answer" class="form-label">回答</label>
            <textarea id="answer" name="answer" rows="6" required
                      placeholder="回答を入力してください"
                      class="form-textarea">{{ old('answer', $question->answer) }}</textarea>
            @error('answer')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-3 border-t border-secondary-200">
            <a href="{{ route('model.questions.index') }}"
               class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
                Cancel
            </a>
            <button type="submit"
                    class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Save
            </button>
        </div>
    </form>

</div>
@endsection
