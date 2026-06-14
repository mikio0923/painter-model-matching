@extends('layouts.app')

@section('title', $information->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($information->content ?? ''), 150))

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('information.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Information
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current truncate max-w-xs">{{ $information->title }}</span>
        </div>

        <div class="flex items-center gap-4 mb-2">
            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">
                {{ $information->published_at->format('Y . n . j') }}
            </p>
            <p class="text-[10px] tracking-[0.25em] uppercase {{ $information->type === 'press_release' ? 'text-secondary-900 border-y border-secondary-300 px-2 py-0.5' : 'text-secondary-500 border-y border-secondary-200 px-2 py-0.5' }}">
                {{ $information->type === 'press_release' ? 'Press' : 'Notice' }}
            </p>
        </div>

        <h1 class="page-header-title mt-3">{{ $information->title }}</h1>
    </div>
</div>

<div class="page-narrow">
    <article class="border border-secondary-200 bg-canvas-50 px-5 sm:px-8 py-8 sm:py-10">
        @if($information->content)
            <div class="prose-custom text-secondary-700 leading-relaxed text-[15px] whitespace-pre-wrap">
                {!! nl2br(e($information->content)) !!}
            </div>
        @endif
    </article>

    <div class="mt-8 text-center">
        <a href="{{ route('information.index') }}" class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
            ← Back to Information
        </a>
    </div>
</div>
@endsection
