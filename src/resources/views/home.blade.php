@extends('layouts.app')

@section('content')

{{-- ========== HERO ========== --}}
<section class="hero" style="background-image: linear-gradient(135deg, #1e0a3c 0%, #3b0764 30%, #6d28d9 65%, #be123c 100%);">
    {{-- 装飾ドット --}}
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
    <div class="hero-content text-center">
        <p class="font-display tracking-[0.2em] text-xs sm:text-sm uppercase text-white/50 mb-4">Art × Portrait Matching Platform</p>
        <h1 class="hero-title mb-5">
            画家とモデルを<br class="sm:hidden">つなぐ場所
        </h1>
        <p class="hero-subtitle mx-auto text-center">
            ポートレート・人物画の制作に特化した<br>クリエイター同士のマッチングサービス
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('models.index') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-primary-800 font-bold text-base rounded-xl shadow-lg hover:bg-canvas-50 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                モデルを探す
            </a>
            <a href="{{ route('jobs.index') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/10 backdrop-blur-sm text-white font-bold text-base rounded-xl border border-white/30 hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                依頼を見る
            </a>
        </div>
    </div>
    {{-- ヒーロー下部ウェーブ --}}
    <div class="absolute bottom-0 left-0 right-0 overflow-hidden leading-none">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-14" style="display:block;">
            <path d="M0,32 C240,56 480,0 720,28 C960,56 1200,8 1440,32 L1440,56 L0,56 Z" fill="#f9f7f5"/>
        </svg>
    </div>
</section>

<div class="page space-y-20">

    {{-- ========== PICKUP MODELS ========== --}}
    @if($pickupModels->count() > 0)
    <section class="animate-fade-in">
        <div class="section-header">
            <div>
                <p class="section-title-en mb-2">Pickup Model</p>
                <h2 class="section-title">注目のモデル</h2>
            </div>
            <a href="{{ route('models.index') }}" class="link-arrow shrink-0 text-sm">
                すべて見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
            @foreach($pickupModels as $model)
            @php
                $isFav = in_array($model->id, $favoriteModelIds ?? []);
                $genderClass = match($model->gender ?? '') {
                    'male'   => 'model-card-male',
                    'female' => 'model-card-female',
                    default  => 'model-card-other',
                };
            @endphp
            <div class="model-card {{ $genderClass }} group">
                <a href="{{ route('models.show', $model) }}" class="block">
                    <div class="model-card-image">
                        @if($model->profile_image_path)
                            <img src="{{ Storage::url($model->profile_image_path) }}" alt="{{ $model->display_name }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-accent-100">
                                <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                        <div class="model-card-overlay"></div>
                    </div>
                    <div class="model-card-info">
                        <p class="text-sm font-semibold text-secondary-900 truncate leading-tight">{{ $model->display_name }}</p>
                        <p class="text-xs text-secondary-400 mt-0.5 truncate">
                            @if($model->prefecture){{ $model->prefecture }}@endif
                            @if($model->age) · {{ $model->age }}歳@endif
                        </p>
                        @if($model->reward_min || $model->reward_max)
                            <p class="text-xs font-semibold text-primary-600 mt-1">
                                @if($model->reward_min){{ number_format($model->reward_min) }}円〜
                                @elseif($model->reward_max)〜{{ number_format($model->reward_max) }}円
                                @endif
                            </p>
                        @endif
                        @php $tags = $model->style_tags ?? []; @endphp
                        @if(count($tags) > 0)
                            <div class="mt-1.5 flex flex-wrap gap-0.5">
                                @foreach(array_slice($tags, 0, 2) as $tag)
                                    <span class="inline-block px-1.5 py-0 rounded text-[10px] font-medium bg-primary-50 text-primary-600">{{ $tag }}</span>
                                @endforeach
                                @if(count($tags) > 2)<span class="text-[10px] text-secondary-400">+{{ count($tags) - 2 }}</span>@endif
                            </div>
                        @endif
                    </div>
                </a>
                @auth
                <div class="absolute top-2 right-2 z-10" onclick="event.stopPropagation();">
                    @if($isFav)
                    <form method="POST" action="{{ route('favorites.destroy.model', $model) }}" class="inline js-ajax-favorite-home">@csrf @method('DELETE')
                        <button type="submit" class="fav-btn-active" title="お気に入り解除">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('favorites.store.model', $model) }}" class="inline js-ajax-favorite-home">@csrf
                        <button type="submit" class="fav-btn" title="お気に入りに追加">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </form>
                    @endif
                </div>
                @endauth
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ========== PICKUP JOBS ========== --}}
    @if($pickupJobs->count() > 0)
    <section>
        <div class="section-header">
            <div>
                <p class="section-title-en mb-2">Pickup Job</p>
                <h2 class="section-title">注目の依頼</h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="link-arrow shrink-0 text-sm">
                すべて見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($pickupJobs as $job)
            @php
                $painter      = $job->painter;
                $painterProfile = $painter->painterProfile ?? null;
                $painterName  = $painterProfile?->display_name ?? $painter->name;
                $painterImage = $painterProfile?->profile_image_path ?? null;
                $isFavJob     = in_array($job->id, $favoriteJobIds ?? []);
            @endphp
            <div class="job-card group relative">
                <a href="{{ route('jobs.show', $job) }}" class="block job-card-body hover:opacity-100">
                    {{-- 画家情報 --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="avatar avatar-md border-2 border-primary-100">
                            @if($painterImage)
                                <img src="{{ Storage::url($painterImage) }}" alt="{{ $painterName }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-secondary-800 truncate">{{ $painterName }}</p>
                            <p class="text-xs text-secondary-400">画家</p>
                        </div>
                        <div class="ml-auto">
                            <span class="status-open">公開中</span>
                        </div>
                    </div>

                    <h3 class="text-base font-bold text-secondary-900 line-clamp-2 mb-2 leading-snug">{{ $job->title }}</h3>
                    <p class="text-sm text-secondary-500 line-clamp-2 mb-4 leading-relaxed">
                        {{ mb_strlen($job->description) > 100 ? mb_substr($job->description, 0, 100) . '…' : $job->description }}
                    </p>

                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-secondary-100 mt-auto">
                        <span class="inline-flex items-center gap-1 text-xs text-secondary-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}@if($job->prefecture)・{{ $job->prefecture }}@endif
                        </span>
                        @if($job->reward_amount)
                        <span class="ml-auto text-sm font-bold text-primary-600">
                            {{ number_format($job->reward_amount) }}円
                            <span class="font-normal text-xs text-secondary-400">{{ $job->reward_unit === 'per_hour' ? '/時間' : '/回' }}</span>
                        </span>
                        @endif
                    </div>
                </a>
                @auth
                <div class="absolute top-3 right-3 z-10">
                    @if($isFavJob)
                    <form method="POST" action="{{ route('favorites.destroy.job', $job) }}" class="inline js-ajax-favorite-home">@csrf @method('DELETE')
                        <button type="submit" class="fav-btn-active" title="お気に入り解除"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg></button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('favorites.store.job', $job) }}" class="inline js-ajax-favorite-home">@csrf
                        <button type="submit" class="fav-btn" title="お気に入りに追加"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></button>
                    </form>
                    @endif
                </div>
                @endauth
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ========== REVIEWS ========== --}}
    @if($latestReviews->count() > 0)
    <section>
        <div class="section-header">
            <div>
                <p class="section-title-en mb-1">Review</p>
                <h2 class="section-title">新着レビュー</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($latestReviews as $review)
            @php
                $reviewee     = $review->reviewedUser;
                $revieweeProfile = $reviewee->modelProfile ?? $reviewee->painterProfile;
                $revieweeName = $revieweeProfile?->display_name ?? $reviewee->name;
                $revieweeImage = $reviewee->modelProfile?->profile_image_path ?? null;
                $revieweeRole = $reviewee->role === 'model' ? 'モデル' : '画家';
                $revieweeUrl  = $reviewee->role === 'model' && $reviewee->modelProfile
                    ? route('models.show', $reviewee->modelProfile) : null;
                $starCount    = match($review->rating) { 'very_good' => 5, 'good' => 3, 'bad' => 1, default => 0 };
                $reviewerName = $review->reviewer->modelProfile?->display_name ?? $review->reviewer->painterProfile?->display_name ?? $review->reviewer->name;
            @endphp
            <div class="bg-white rounded-2xl shadow-card p-5 flex flex-col gap-3">
                {{-- ユーザー情報 --}}
                <div class="flex items-center gap-3">
                    @if($revieweeUrl)<a href="{{ $revieweeUrl }}" class="block avatar avatar-md hover:opacity-80 transition-opacity">@else<div class="avatar avatar-md">@endif
                        @if($revieweeImage)
                            <img src="{{ Storage::url($revieweeImage) }}" alt="{{ $revieweeName }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-5 h-5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @endif
                    @if($revieweeUrl)</a>@else</div>@endif
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-secondary-900 truncate">{{ $revieweeName }}</p>
                        <p class="text-xs text-secondary-400">{{ $revieweeRole }}</p>
                    </div>
                </div>
                {{-- 星 --}}
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $starCount ? 'text-gold-500' : 'text-secondary-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                {{-- コメント --}}
                <p class="text-sm text-secondary-600 leading-relaxed line-clamp-4 flex-1">
                    {{ $review->comment ?: 'コメントなし' }}
                </p>
                <p class="text-xs text-secondary-400 border-t border-secondary-100 pt-2">{{ $reviewerName }} より · {{ $review->created_at->format('Y.m.d') }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ========== NEW MODELS ========== --}}
    @if(isset($models) && $models->count() > 0)
    <section>
        <div class="section-header">
            <div>
                <p class="section-title-en mb-2">New Models</p>
                <h2 class="section-title">新着モデル</h2>
            </div>
            <a href="{{ route('models.index') }}" class="link-arrow shrink-0 text-sm">
                すべて見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
            @foreach($models as $model)
            @php
                $isFavNew = in_array($model->id, $favoriteModelIds ?? []);
                $genderClass = match($model->gender ?? '') {
                    'male'   => 'model-card-male',
                    'female' => 'model-card-female',
                    default  => 'model-card-other',
                };
            @endphp
            <div class="model-card {{ $genderClass }} group">
                <a href="{{ route('models.show', $model) }}" class="block">
                    <div class="model-card-image">
                        @if($model->profile_image_path)
                            <img src="{{ Storage::url($model->profile_image_path) }}" alt="{{ $model->display_name }}" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-accent-50">
                                <svg class="w-10 h-10 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                        <div class="model-card-overlay"></div>
                    </div>
                    <div class="model-card-info">
                        <p class="text-sm font-semibold text-secondary-900 truncate">{{ $model->display_name }}</p>
                        <p class="text-xs text-secondary-400 mt-0.5">
                            {{ $model->prefecture }}{{ $model->age ? ' · ' . $model->age . '歳' : '' }}
                        </p>
                        @if($model->reward_min || $model->reward_max)
                            <p class="text-xs font-semibold text-primary-600 mt-1">
                                @if($model->reward_min)
                                    {{ number_format($model->reward_min) }}円〜
                                @elseif($model->reward_max)
                                    〜{{ number_format($model->reward_max) }}円
                                @endif
                            </p>
                        @endif
                    </div>
                </a>
                @auth
                <div class="absolute top-2 right-2 z-10" onclick="event.stopPropagation();">
                    @if($isFavNew)
                    <form method="POST" action="{{ route('favorites.destroy.model', $model) }}" class="inline js-ajax-favorite-home">@csrf @method('DELETE')
                        <button type="submit" class="fav-btn-active"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg></button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('favorites.store.model', $model) }}" class="inline js-ajax-favorite-home">@csrf
                        <button type="submit" class="fav-btn"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></button>
                    </form>
                    @endif
                </div>
                @endauth
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ========== HIGH RATING REVIEWS ========== --}}
    @if(isset($highRatingReviews) && $highRatingReviews->count() > 0)
    <section>
        <div class="section-header">
            <div>
                <p class="section-title-en mb-1">Voices</p>
                <h2 class="section-title">高評価レビュー</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($highRatingReviews as $review)
            @php
                $profileImage = null;
                $reviewerName = $review->reviewer->name;
                if ($review->reviewer->role === 'model' && $review->reviewer->modelProfile) {
                    $profileImage = $review->reviewer->modelProfile->profile_image_path;
                    $reviewerName = $review->reviewer->modelProfile->display_name;
                } elseif ($review->reviewer->role === 'painter' && $review->reviewer->painterProfile) {
                    $reviewerName = $review->reviewer->painterProfile->display_name;
                }
            @endphp
            <div class="bg-white rounded-2xl shadow-card p-6 flex gap-4">
                <div class="avatar avatar-md shrink-0 border-2 border-primary-100">
                    @if($profileImage)
                        <img src="{{ Storage::url($profileImage) }}" alt="{{ $reviewerName }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-sm font-semibold text-secondary-900 truncate">{{ $reviewerName }}</p>
                        <span class="badge badge-success shrink-0">{{ $review->rating_label }}</span>
                    </div>
                    @if($review->comment)
                        <p class="text-sm text-secondary-600 leading-relaxed line-clamp-3">{{ $review->comment }}</p>
                    @endif
                    <p class="text-xs text-secondary-400 mt-2">{{ $review->created_at->format('Y.m.d') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ========== INFORMATION / PRESS / NEW JOBS ========== --}}
    @php
        $hasInfo = isset($informations) && $informations->count() > 0;
        $hasPress = isset($pressReleases) && $pressReleases->count() > 0;
        $hasNewJobs = isset($newJobs) && $newJobs->count() > 0;
    @endphp
    @if($hasInfo || $hasPress || $hasNewJobs)
    <section>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($hasInfo)
            <div class="bg-white rounded-2xl shadow-card p-6">
                <h3 class="font-display text-lg font-bold text-secondary-900 border-b border-secondary-100 pb-3 mb-4">
                    <span class="text-primary-600 mr-2">—</span> お知らせ
                </h3>
                <ul class="space-y-3">
                    @foreach($informations as $info)
                    <li>
                        <a href="{{ route('information.show', $info) }}" class="group block">
                            <p class="text-xs text-secondary-400 mb-0.5">{{ $info->published_at?->format('Y.m.d') }}</p>
                            <p class="text-sm text-secondary-700 group-hover:text-primary-700 transition-colors line-clamp-2 leading-snug">{{ $info->title }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($hasPress)
            <div class="bg-white rounded-2xl shadow-card p-6">
                <h3 class="font-display text-lg font-bold text-secondary-900 border-b border-secondary-100 pb-3 mb-4">
                    <span class="text-accent-600 mr-2">—</span> プレスリリース
                </h3>
                <ul class="space-y-3">
                    @foreach($pressReleases as $press)
                    <li>
                        <a href="{{ route('information.show', $press) }}" class="group block">
                            <p class="text-xs text-secondary-400 mb-0.5">{{ $press->published_at?->format('Y.m.d') }}</p>
                            <p class="text-sm text-secondary-700 group-hover:text-accent-700 transition-colors line-clamp-2 leading-snug">{{ $press->title }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($hasNewJobs)
            <div class="bg-white rounded-2xl shadow-card p-6">
                <h3 class="font-display text-lg font-bold text-secondary-900 border-b border-secondary-100 pb-3 mb-4">
                    <span class="text-gold-600 mr-2">—</span> 新着依頼
                </h3>
                <ul class="space-y-3">
                    @foreach($newJobs as $job)
                    <li>
                        <a href="{{ route('jobs.show', $job) }}" class="group block">
                            <p class="text-xs text-secondary-400 mb-0.5">{{ $job->created_at->format('Y.m.d') }}</p>
                            <p class="text-sm text-secondary-700 group-hover:text-gold-700 transition-colors line-clamp-2 leading-snug">{{ $job->title }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    <a href="{{ route('jobs.index') }}" class="link-arrow text-xs">
                        依頼一覧へ
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

</div>
@endsection
