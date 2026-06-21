{{--
    星評価の表示専用コンポーネント（read-only）
    使用例:
        <x-star-rating :rating="$review->rating" />
        <x-star-rating :rating="4" :show-number="true" />

    入力用は別途 reviews/create のフォーム内で実装。
--}}
@props([
    'rating' => 0,        // 1〜5 の整数
    'size' => 'md',       // 'sm' | 'md' | 'lg'
    'showNumber' => false, // 「★ 4 / 5」のような数値併記
])

@php
    $r = max(0, min(5, (int) $rating));
    $sizeClass = match($size) {
        'sm' => 'w-3.5 h-3.5',
        'lg' => 'w-6 h-6',
        default => 'w-4 h-4',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5 align-middle']) }}>
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $r)
            <svg class="{{ $sizeClass }} text-warning-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.16c.969 0 1.371 1.24.588 1.81l-3.366 2.446a1 1 0 00-.364 1.118l1.286 3.957c.299.921-.755 1.688-1.538 1.118l-3.366-2.446a1 1 0 00-1.176 0L5.745 17.02c-.783.57-1.837-.197-1.538-1.118l1.286-3.957a1 1 0 00-.364-1.118L1.763 9.384c-.783-.57-.38-1.81.588-1.81h4.16a1 1 0 00.95-.69l1.286-3.957z"/>
            </svg>
        @else
            <svg class="{{ $sizeClass }} text-secondary-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.16c.969 0 1.371 1.24.588 1.81l-3.366 2.446a1 1 0 00-.364 1.118l1.286 3.957c.299.921-.755 1.688-1.538 1.118l-3.366-2.446a1 1 0 00-1.176 0L5.745 17.02c-.783.57-1.837-.197-1.538-1.118l1.286-3.957a1 1 0 00-.364-1.118L1.763 9.384c-.783-.57-.38-1.81.588-1.81h4.16a1 1 0 00.95-.69l1.286-3.957z"/>
            </svg>
        @endif
    @endfor
    @if($showNumber)
        <span class="text-xs text-secondary-500 ml-1">{{ $r }} / 5</span>
    @endif
    <span class="sr-only">{{ $r }} / 5</span>
</span>
