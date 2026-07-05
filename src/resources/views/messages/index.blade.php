@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Messages</p>
        <h1 class="page-header-title mt-2">メッセージ</h1>
        <p class="text-secondary-500 text-sm mt-3">承認済みの依頼について、画家とモデル間でやり取りできます。</p>
    </div>
</div>

<div class="page-narrow">

    @if($threads->isEmpty())
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Messages</p>
            <p class="text-secondary-600 mb-2">メッセージはまだありません。</p>
            <p class="text-xs text-secondary-500 leading-relaxed max-w-sm mx-auto">
                依頼への応募が承認されると、画家とモデル間でやり取りができるようになります。
            </p>
        </div>
    @else
        <div class="border-t border-l border-secondary-200">
            @foreach($threads as $thread)
                @continue(!$thread->job || !$thread->other_user)
                <a href="{{ route('messages.show', ['job' => $thread->job, 'with' => $thread->other_user->id]) }}"
                   class="group flex items-start justify-between gap-4 px-5 py-5 border-r border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">
                                {{ $thread->other_user->name ?? '退会済みユーザー' }}
                            </p>
                            @if($thread->unread_count > 0)
                                <span class="inline-flex items-center justify-center min-w-[18px] h-4 px-1 bg-secondary-900 text-canvas-50 text-[9px] font-medium">
                                    {{ $thread->unread_count > 99 ? '99+' : $thread->unread_count }}
                                </span>
                            @endif
                        </div>
                        <h2 class="font-display text-base font-semibold text-secondary-900 truncate mb-2">
                            {{ $thread->job->title }}
                        </h2>
                        @if($thread->last_message)
                            <p class="text-sm text-secondary-600 line-clamp-2 leading-relaxed">
                                {{ $thread->last_message->body }}
                            </p>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400 mt-2">
                                {{ $thread->last_message->created_at->format('Y . n . j  H:i') }}
                            </p>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-secondary-300 group-hover:text-secondary-900 transition-colors duration-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
