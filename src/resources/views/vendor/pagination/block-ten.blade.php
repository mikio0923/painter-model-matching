@if ($paginator->hasPages())
    @php
        $blockSize    = 10;
        $current      = $paginator->currentPage();
        $last         = $paginator->lastPage();
        $blockIndex   = (int) ceil($current / $blockSize);                       // 1-based
        $blockStart   = ($blockIndex - 1) * $blockSize + 1;
        $blockEnd     = min($blockIndex * $blockSize, $last);
        $hasPrevBlock = $blockStart > 1;
        $hasNextBlock = $blockEnd < $last;

        $linkBase     = 'inline-flex items-center justify-center min-w-[36px] h-9 px-3 text-sm border border-secondary-300 text-secondary-700 hover:bg-secondary-100 transition-colors';
        $disabledBase = 'inline-flex items-center justify-center min-w-[36px] h-9 px-3 text-sm border border-secondary-200 text-secondary-300 cursor-not-allowed';
        $activeBase   = 'inline-flex items-center justify-center min-w-[36px] h-9 px-3 text-sm bg-secondary-900 text-canvas-50 border border-secondary-900';
    @endphp

    <nav role="navigation" aria-label="ページネーション" class="flex flex-wrap items-center justify-center gap-1.5">

        {{-- 前の10ページ --}}
        @if ($hasPrevBlock)
            <a href="{{ $paginator->url($blockStart - 1) }}" rel="prev" class="{{ $linkBase }}" aria-label="前の10ページ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            </a>
        @else
            <span class="{{ $disabledBase }}" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            </span>
        @endif

        {{-- 前のページ --}}
        @if ($paginator->onFirstPage())
            <span class="{{ $disabledBase }}" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $linkBase }}" aria-label="前のページ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- 現在ブロックのページ番号 --}}
        @for ($p = $blockStart; $p <= $blockEnd; $p++)
            @if ($p == $current)
                <span class="{{ $activeBase }}" aria-current="page">{{ $p }}</span>
            @else
                <a href="{{ $paginator->url($p) }}" class="{{ $linkBase }}">{{ $p }}</a>
            @endif
        @endfor

        {{-- 次のページ --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $linkBase }}" aria-label="次のページ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="{{ $disabledBase }}" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif

        {{-- 次の10ページ --}}
        @if ($hasNextBlock)
            <a href="{{ $paginator->url($blockEnd + 1) }}" rel="next" class="{{ $linkBase }}" aria-label="次の10ページ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="{{ $disabledBase }}" aria-hidden="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            </span>
        @endif
    </nav>
@endif
