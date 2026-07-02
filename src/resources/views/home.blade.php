@extends('layouts.app')

@section('content')

{{-- ========== HERO（美術館トーン） ========== --}}
<section class="hero">
    {{-- 背景アートレイヤー（名画がゆっくり Ken Burns で循環） --}}
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>

    <div class="hero-content text-center">
        <p class="museum-label">An Art × Portrait Matching Platform</p>
        <h1 class="hero-title mb-4 mt-2">
            画家とモデルを、<br class="sm:hidden">静かにつなぐ
        </h1>
        <p class="hero-subtitle mx-auto text-center">
            ポートレート・人物画の制作に特化した<br>クリエイター同士のマッチング。
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('models.index') }}" class="btn-museum-dark w-full sm:w-auto">
                モデルを探す
            </a>
            <a href="{{ route('jobs.index') }}" class="btn-museum-outline w-full sm:w-auto">
                依頼を見る
            </a>
        </div>

        {{-- キュレーター・キャプション --}}
        <div class="mt-10 max-w-md mx-auto art-caption text-left">
            ポートレートは、被写体と画家の対話の記録である。
            <span class="block mt-2 text-xs text-secondary-400 not-italic tracking-[0.2em] uppercase">— Curator's Note</span>
        </div>
    </div>
</section>

<div class="page space-y-24">

    {{-- ========== PICKUP MODELS ========== --}}
    @if($pickupModels->count() > 0)
    <section class="animate-fade-in">
        <div class="section-header">
            <div>
                <p class="museum-label">Pickup Model</p>
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
                    </div>
                </a>
                <div class="absolute top-2 right-2 z-10">
                    <x-favorite-button type="model" :id="$model->id" :favorited="$isFav" />
                </div>
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
                <p class="museum-label">Pickup Job</p>
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
                <div class="absolute top-3 right-3 z-10">
                    <x-favorite-button type="job" :id="$job->id" :favorited="$isFavJob" />
                </div>
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
                <p class="museum-label">Review</p>
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
                $starCount    = (int) $review->rating;
                $reviewerName = $review->reviewer->modelProfile?->display_name ?? $review->reviewer->painterProfile?->display_name ?? $review->reviewer->name;
            @endphp
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-5 flex flex-col gap-3">
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
                <x-star-rating :rating="$starCount" />
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
                <p class="museum-label">New Models</p>
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
                <div class="absolute top-2 right-2 z-10">
                    <x-favorite-button type="model" :id="$model->id" :favorited="$isFavNew" />
                </div>
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
                <p class="museum-label">Voices</p>
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
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-6 flex gap-4">
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
                        <x-star-rating :rating="(int) $review->rating" size="sm" />
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
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-6">
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
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-6">
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
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-6">
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
