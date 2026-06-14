@extends('layouts.app')

@section('title', 'お知らせ')
@section('description', 'Palette からのお知らせ・プレスリリース一覧。')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Information</p>
        <h1 class="page-header-title mt-2">お知らせ</h1>
        <p class="text-secondary-500 text-sm mt-3">運営からのお知らせ・プレスリリースをお届けします。</p>
    </div>
</div>

<div class="page-narrow">
    @if($informations->isEmpty())
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Information</p>
            <p class="text-secondary-500">現在お知らせはありません。</p>
        </div>
    @else
        <div class="border-t border-secondary-200">
            @foreach($informations as $info)
                <a href="{{ route('information.show', $info) }}"
                   class="group flex items-start gap-5 sm:gap-8 py-6 px-1 border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300">
                    <div class="shrink-0 text-left">
                        <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                            {{ $info->published_at->format('Y . n . j') }}
                        </p>
                        <p class="text-[10px] tracking-[0.25em] uppercase mt-1 {{ $info->type === 'press_release' ? 'text-secondary-900' : 'text-secondary-500' }}">
                            {{ $info->type === 'press_release' ? 'Press' : 'Notice' }}
                        </p>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-display text-base sm:text-lg font-semibold text-secondary-900 mb-1 leading-snug">
                            {{ $info->title }}
                        </h2>
                        @if($info->content)
                            <p class="text-sm text-secondary-500 line-clamp-2 leading-relaxed">
                                {{ strip_tags($info->content) }}
                            </p>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-secondary-300 group-hover:text-secondary-900 transition-colors duration-300 shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $informations->links() }}
        </div>
    @endif
</div>
@endsection
