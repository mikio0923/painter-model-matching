{{--
    お気に入りボタン（AJAXトグル）

    使用例:
        小さなアイコンボタン（カード右上等）:
            <x-favorite-button type="model" :id="$model->id" :favorited="$isFav" />

        ラベル付き大ボタン（詳細ページ等）:
            <x-favorite-button
                type="model" :id="$model->id" :favorited="$isFav"
                variant="large"
                label-on="お気に入り解除"
                label-off="お気に入りに追加" />

    props:
        - type: 'model' | 'job'
        - id: 対象ID
        - favorited: bool
        - variant: 'icon' | 'large'  (デフォルト 'icon')
        - label-on / label-off: 大ボタン時のラベル
        - size: 'sm' | 'md' | 'lg'  (icon variant 時のみ)
--}}
@props([
    'type' => 'model',
    'id' => null,
    'favorited' => false,
    'variant' => 'icon',
    'labelOn' => 'お気に入り解除',
    'labelOff' => 'お気に入りに追加',
    'size' => 'md',
    'colorOn' => 'dark', // 'dark' | 'red'
])

@php
    $sizeClasses = match($size) {
        'sm' => 'p-1 [&_svg]:w-3 [&_svg]:h-3',
        'lg' => 'p-2 [&_svg]:w-5 [&_svg]:h-5',
        default => 'p-1.5 [&_svg]:w-4 [&_svg]:h-4',
    };
    $isLarge = $variant === 'large';

    // large variant の登録済 / 未登録時の class セット
    // colorOn='red' を指定すると登録済時に赤背景固定（モデル詳細用）
    if ($colorOn === 'red') {
        $favoritedClasses   = 'bg-white text-error-500 border-error-500 hover:bg-error-50';
        $unfavoritedClasses = 'bg-error-500 text-white border-error-500 hover:bg-error-600 hover:border-error-600';
    } else {
        $favoritedClasses   = 'bg-secondary-900 text-canvas-50 border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900';
        $unfavoritedClasses = 'bg-canvas-50 text-secondary-900 border-secondary-900 hover:bg-secondary-900 hover:text-canvas-50';
    }
@endphp

@auth
<button type="button"
        data-favorite-toggle
        data-target-type="{{ $type }}"
        data-target-id="{{ $id }}"
        data-label-on="{{ $labelOn }}"
        data-label-off="{{ $labelOff }}"
        data-color-on="{{ $colorOn }}"
        aria-pressed="{{ $favorited ? 'true' : 'false' }}"
        title="{{ $favorited ? $labelOn : $labelOff }}"
        @if($isLarge)
            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 border text-sm font-medium transition-colors duration-300 {{ $favorited ? $favoritedClasses : $unfavoritedClasses }}"
        @else
            class="{{ $favorited ? 'fav-btn-active' : 'fav-btn' }} {{ $sizeClasses }} transition-colors duration-200"
        @endif
        >
    {{-- 未登録: outline ハート --}}
    <svg data-favorite-icon="off" class="{{ $favorited ? 'hidden' : '' }} {{ $isLarge ? 'w-4 h-4' : '' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>
    {{-- 登録済: filled ハート --}}
    <svg data-favorite-icon="on" class="{{ $favorited ? '' : 'hidden' }} {{ $isLarge ? 'w-4 h-4' : '' }}" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
    </svg>
    @if($isLarge)
        <span data-favorite-label>{{ $favorited ? $labelOn : $labelOff }}</span>
    @endif
</button>
@endauth
