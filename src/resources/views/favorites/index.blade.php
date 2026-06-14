@extends('layouts.app')

@section('title', 'お気に入り')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Favorites</p>
        <h1 class="page-header-title mt-2">お気に入り</h1>
        <p class="text-secondary-500 text-sm mt-3">気になるモデル・依頼をブックマークしてまとめて確認できます。</p>
    </div>
</div>

<div class="page">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Updated</p>
            {{ session('success') }}
        </div>
    @endif

    @if($favorites->count() === 0)
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Favorites</p>
            <p class="text-secondary-500 text-sm mb-6">まだお気に入りはありません。</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('models.index') }}" class="btn-museum-outline">モデルを探す</a>
                <a href="{{ route('jobs.index') }}" class="btn-museum-outline">依頼を見る</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($favorites as $favorite)
                @php $item = $favorite->favoritable; @endphp

                @if($item instanceof \App\Models\ModelProfile)
                    {{-- モデルプロフィール --}}
                    <div class="relative border border-secondary-200 bg-canvas-50 overflow-hidden hover:border-secondary-400 transition-colors duration-300">
                        <div class="absolute top-2 right-2 z-20">
                            <x-favorite-button type="model" :id="$item->id" :favorited="true" />
                        </div>
                        <a href="{{ route('models.show', $item) }}" class="block">
                            <div class="aspect-[3/4] bg-secondary-100 overflow-hidden">
                                @if($item->profile_image_path)
                                    <img src="{{ Storage::url($item->profile_image_path) }}"
                                         alt="{{ $item->display_name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-secondary-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-1">Model</p>
                                <h3 class="font-display text-base font-semibold text-secondary-900 mb-1 truncate">{{ $item->display_name }}</h3>
                                <p class="text-xs text-secondary-500">
                                    @if($item->prefecture){{ $item->prefecture }}@endif
                                    @if($item->age) · {{ $item->age }}歳@endif
                                </p>
                            </div>
                        </a>
                    </div>

                @elseif($item instanceof \App\Models\Job)
                    {{-- 依頼（モデルカードと同サイズ） --}}
                    <div class="relative border border-secondary-200 bg-canvas-50 overflow-hidden hover:border-secondary-400 transition-colors duration-300">
                        <div class="absolute top-2 right-2 z-20">
                            <x-favorite-button type="job" :id="$item->id" :favorited="true" />
                        </div>
                        <a href="{{ route('jobs.show', $item) }}" class="block">
                            {{-- 報酬を大きく表示する装飾エリア（モデルの画像 aspect-[3/4] と同サイズ） --}}
                            <div class="aspect-[3/4] bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-700 text-canvas-50 p-5 flex flex-col">
                                <p class="text-[10px] tracking-[0.3em] uppercase text-canvas-50/60 mb-2">Job</p>
                                <h3 class="font-display text-base font-semibold leading-snug line-clamp-3 mb-3">{{ $item->title }}</h3>
                                <p class="text-[11px] text-canvas-50/70 line-clamp-4 leading-relaxed mb-auto">
                                    {{ mb_strlen($item->description) > 120 ? mb_substr($item->description, 0, 120) . '…' : $item->description }}
                                </p>
                                @if($item->reward_amount)
                                    <div class="pt-3 mt-3 border-t border-canvas-50/15">
                                        <p class="text-[9px] tracking-[0.3em] uppercase text-canvas-50/50">Reward</p>
                                        <p class="font-display text-2xl font-medium mt-0.5">
                                            ¥{{ number_format($item->reward_amount) }}<span class="text-xs text-canvas-50/60 font-normal ml-1">{{ $item->reward_unit === 'per_hour' ? '/時間' : '/回' }}</span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            {{-- メタ情報（モデルカードの p-4 と同じ） --}}
                            <div class="p-4">
                                <div class="space-y-1.5 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-secondary-400">場所</span>
                                        <span class="text-secondary-700 font-medium">
                                            {{ $item->location_type === 'online' ? 'オンライン' : 'オフライン' }}@if($item->prefecture)（{{ $item->prefecture }}）@endif
                                        </span>
                                    </div>
                                    @if($item->scheduled_date)
                                        <div class="flex items-center justify-between">
                                            <span class="text-secondary-400">日程</span>
                                            <span class="text-secondary-700">{{ $item->scheduled_date->format('Y/n/j') }}</span>
                                        </div>
                                    @endif
                                    @if($item->apply_deadline)
                                        <div class="flex items-center justify-between">
                                            <span class="text-secondary-400">締切</span>
                                            <span class="text-secondary-700">{{ $item->apply_deadline->format('Y/n/j') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mt-10">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection
