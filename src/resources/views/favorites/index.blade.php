@extends('layouts.app')

@section('title', 'お気に入り')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
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
                    {{-- 依頼 --}}
                    <div class="relative border border-secondary-200 bg-canvas-50 hover:border-secondary-400 transition-colors duration-300 flex flex-col">
                        <div class="absolute top-2 right-2 z-20">
                            <x-favorite-button type="job" :id="$item->id" :favorited="true" />
                        </div>
                        <a href="{{ route('jobs.show', $item) }}" class="block p-5 flex flex-col flex-1">
                            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Job</p>
                            <h3 class="font-display text-base font-semibold text-secondary-900 mb-2 line-clamp-2 leading-snug">{{ $item->title }}</h3>
                            <p class="text-xs text-secondary-500 line-clamp-2 mb-4 leading-relaxed">
                                {{ mb_strlen($item->description) > 80 ? mb_substr($item->description, 0, 80) . '…' : $item->description }}
                            </p>
                            <div class="mt-auto pt-3 border-t border-secondary-200 flex items-center justify-between text-xs">
                                <span class="text-secondary-500">
                                    {{ $item->location_type === 'online' ? 'Online' : 'Offline' }}
                                    @if($item->prefecture)
                                        · {{ $item->prefecture }}
                                    @endif
                                </span>
                                @if($item->reward_amount)
                                    <span class="font-semibold text-secondary-900">
                                        ¥{{ number_format($item->reward_amount) }}
                                    </span>
                                @endif
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
