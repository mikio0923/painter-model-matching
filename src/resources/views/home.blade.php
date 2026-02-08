@extends('layouts.app')

@section('content')
<div class="page">

    {{-- ========== ピックアップモデル欄 ========== --}}
    @if($pickupModels->count() > 0)
        <section class="section mb-16">
            <div class="section-header mb-8">
                <h2 class="section-title text-3xl sm:text-4xl">Pickup Model / ピックアップモデル</h2>
                <a href="{{ route('models.index') }}" class="link-primary text-sm">もっと見る →</a>
            </div>
            <div class="section-panel">
                <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-6 gap-3">
                @foreach($pickupModels as $model)
                        <a href="{{ route('models.show', $model) }}" class="card card-hover overflow-hidden">
                        {{-- 画像 --}}
                            <div class="aspect-[3/4] card-media">
                            @if($model->profile_image_path)
                                <img src="{{ Storage::url($model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- 情報 --}}
                        <div class="card-body p-2">
                                <div class="card-title mb-0.5 text-sm">
                                {{ $model->display_name }}
                            </div>

                                <div class="card-meta mb-1 text-xs">
                                @if($model->prefecture)
                                    <span>{{ $model->prefecture }}</span>
                                @endif

                                @if($model->age)
                                    <span class="ml-1">{{ $model->age }}歳</span>
                                @endif

                                @if($model->gender)
                                    <span class="ml-1">
                                        @if($model->gender === 'male')男性
                                        @elseif($model->gender === 'female')女性
                                        @elseその他
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @if($model->reward_min || $model->reward_max)
                                    <div class="card-price mb-1 text-xs">
                                    参考価格：
                                    @if($model->reward_min && $model->reward_max)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_min)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_max)
                                        〜{{ number_format($model->reward_max) }}円
                                    @endif
                                </div>
                            @endif

                            @php $tags = $model->style_tags ?? []; @endphp
                            @if(count($tags) > 0)
                                <div class="mt-1 flex flex-wrap gap-0.5">
                                    @foreach(array_slice($tags, 0, 2) as $tag)
                                        <span class="badge badge-secondary text-xs px-1 py-0">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($tags) > 2)
                                        <span class="text-xs text-secondary-500">+{{ count($tags) - 2 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ========== モデル募集欄 ========== --}}
    @if($pickupJobs->count() > 0)
        <section class="section mb-16">
            <div class="section-header mb-8">
                <h2 class="section-title text-3xl sm:text-4xl">Pickup Job / ピックアップジョブ</h2>
                <a href="{{ route('jobs.index') }}" class="link-primary text-sm">もっと見る →</a>
            </div>
            <div class="section-panel">
                <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pickupJobs as $job)
                            <a href="{{ route('jobs.show', $job) }}" class="card card-hover">
                        <div class="card-body">
                            <h3 class="font-semibold text-lg mb-2 text-secondary-900">{{ $job->title }}</h3>
                            <p class="text-sm text-secondary-600 mb-3 line-clamp-2">
                                {{ mb_strlen($job->description) > 100 ? mb_substr($job->description, 0, 100) . '...' : $job->description }}
                            </p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">
                                    {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                    @if($job->prefecture)
                                        ({{ $job->prefecture }})
                                    @endif
                                </span>
                                @if($job->reward_amount)
                                    <span class="font-semibold text-primary-600">
                                        {{ number_format($job->reward_amount) }}円
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ========== 新着レビュー欄 ========== --}}
    @if($latestReviews->count() > 0)
        <section class="section">
            <div class="section-header">
                <h2 class="section-title">Review / 新着レビュー</h2>
            </div>
            <div class="section-panel">
                <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($latestReviews as $review)
                    @php
                        $reviewee = $review->reviewedUser;
                        $revieweeProfile = $reviewee->modelProfile ?? $reviewee->painterProfile;
                        $revieweeName = $revieweeProfile?->display_name ?? $reviewee->name;
                        $revieweeImage = $reviewee->modelProfile?->profile_image_path ?? null;
                        $revieweeGender = $reviewee->modelProfile?->gender ?? null;
                        $revieweeRole = $reviewee->role === 'model' ? 'モデル' : '画家';
                        $revieweeProfileUrl = $reviewee->role === 'model' && $reviewee->modelProfile
                            ? route('models.show', $reviewee->modelProfile)
                            : ($reviewee->role === 'painter' && $review->job ? route('jobs.show', $review->job) : null);
                        $cardBg = $revieweeGender === 'male' ? 'bg-blue-50 border-blue-200' : (in_array($revieweeGender, ['female', 'other']) ? 'bg-accent-100 border-accent-300' : 'bg-gray-50 border-gray-200');
                        $starCount = match($review->rating) { 'very_good' => 5, 'good' => 3, 'bad' => 1, default => 0 };
                    @endphp
                    <div class="rounded-xl border-2 shadow-sm overflow-hidden {{ $cardBg }}">
                        <div class="p-5">
                            {{-- レビュー受けた側のアイコン＋名前（クリックでプロフィールへ） --}}
                            <div class="flex items-center gap-3 mb-4">
                                @if($revieweeProfileUrl)
                                    <a href="{{ $revieweeProfileUrl }}" class="block w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border-2 {{ $revieweeGender === 'male' ? 'border-blue-300 bg-blue-100' : (in_array($revieweeGender, ['female', 'other']) ? 'border-accent-300 bg-accent-100' : 'border-gray-300 bg-gray-200') }} hover:opacity-90 transition-opacity">
                                @else
                                    <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border-2 {{ $revieweeGender === 'male' ? 'border-blue-300 bg-blue-100' : (in_array($revieweeGender, ['female', 'other']) ? 'border-accent-300 bg-accent-100' : 'border-gray-300 bg-gray-200') }}">
                                @endif
                                    @if($revieweeImage)
                                        <img src="{{ Storage::url($revieweeImage) }}" alt="{{ $revieweeName }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center {{ $revieweeGender === 'male' ? 'text-blue-500' : (in_array($revieweeGender, ['female', 'other']) ? 'text-accent-500' : 'text-gray-500') }}">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                    @endif
                                @if($revieweeProfileUrl)
                                    </a>
                                @else
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-sm text-secondary-900">{{ $revieweeName }}</p>
                                    <p class="text-xs text-secondary-600">{{ $revieweeRole }}</p>
                                </div>
                            </div>

                            {{-- 星評価 --}}
                            <div class="flex gap-0.5 mb-3" aria-label="評価 {{ $starCount }}つ星">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $starCount)
                                        <svg class="w-5 h-5 {{ $revieweeGender === 'male' ? 'text-blue-500' : (in_array($revieweeGender, ['female', 'other']) ? 'text-accent-500' : 'text-amber-500') }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                            </div>

                            {{-- 評価内容 --}}
                            @if($review->comment)
                                <p class="text-sm text-secondary-700 leading-relaxed line-clamp-4">
                                    {{ $review->comment }}
                                </p>
                            @else
                                <p class="text-sm text-secondary-500 italic">
                                    コメントなし
                                </p>
                            @endif

                            {{-- 評価者（小さく） --}}
                            <p class="mt-2 text-xs text-secondary-500">
                                {{ $review->reviewer->modelProfile?->display_name ?? $review->reviewer->painterProfile?->display_name ?? $review->reviewer->name }} より
                            </p>
                        </div>
                    </div>
                @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Image Update Profile --}}
    @if($imageUpdateModels->count() > 0)
        <section class="section mb-16">
            <div class="section-header mb-8">
                <h2 class="section-title text-3xl sm:text-4xl">Image Update Profile / 画像更新されたプロフィール</h2>
                <a href="{{ route('models.index') }}" class="link-primary text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="section-panel">
                <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-6 gap-3">
                    @foreach($imageUpdateModels as $model)
                        <a href="{{ route('models.show', $model) }}" class="card card-hover overflow-hidden">
                        {{-- 画像 --}}
                            <div class="aspect-[3/4] card-media">
                            @if($model->profile_image_path)
                                <img src="{{ Storage::url($model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- 情報 --}}
                        <div class="card-body p-2">
                                <div class="card-title mb-0.5 text-sm">
                                {{ $model->display_name }}
                            </div>

                                <div class="card-meta mb-1 text-xs">
                                @if($model->prefecture)
                                    <span>{{ $model->prefecture }}</span>
                                @endif

                                @if($model->age)
                                    <span class="ml-1">{{ $model->age }}歳</span>
                                @endif

                                @if($model->gender)
                                    <span class="ml-1">
                                        @if($model->gender === 'male')男性
                                        @elseif($model->gender === 'female')女性
                                        @elseその他
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @if($model->reward_min || $model->reward_max)
                                    <div class="card-price mb-1 text-xs">
                                    参考価格：
                                    @if($model->reward_min && $model->reward_max)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_min)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_max)
                                        〜{{ number_format($model->reward_max) }}円
                                    @endif
                                </div>
                            @endif

                            @php $tags = $model->style_tags ?? []; @endphp
                            @if(count($tags) > 0)
                                <div class="mt-1 flex flex-wrap gap-0.5">
                                    @foreach(array_slice($tags, 0, 2) as $tag)
                                        <span class="badge badge-secondary text-xs px-1 py-0">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($tags) > 2)
                                        <span class="text-xs text-secondary-500">+{{ count($tags) - 2 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- 新着モデル --}}
    @if($models->count() > 0)
        <section class="section mb-16">
            <div class="section-header mb-8">
                <h2 class="section-title text-3xl sm:text-4xl">新着モデル</h2>
                <a href="{{ route('models.index') }}" class="link-primary text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="section-panel">
                <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-6 gap-3">
                @foreach($models as $model)
                        <a href="{{ route('models.show', $model) }}" class="card card-hover overflow-hidden">
                        {{-- 画像 --}}
                            <div class="aspect-[3/4] card-media">
                            @if($model->profile_image_path)
                                <img src="{{ Storage::url($model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- 情報 --}}
                        <div class="card-body p-2">
                                <div class="card-title mb-0.5 text-sm">
                                {{ $model->display_name }}
                            </div>

                                <div class="card-meta mb-1 text-xs">
                                @if($model->prefecture)
                                    <span>{{ $model->prefecture }}</span>
                                @endif

                                @if($model->age)
                                    <span class="ml-1">{{ $model->age }}歳</span>
                                @endif

                                @if($model->gender)
                                    <span class="ml-1">
                                        @if($model->gender === 'male')男性
                                        @elseif($model->gender === 'female')女性
                                        @elseその他
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @if($model->reward_min || $model->reward_max)
                                    <div class="card-price mb-1 text-xs">
                                    参考価格：
                                    @if($model->reward_min && $model->reward_max)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_min)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_max)
                                        〜{{ number_format($model->reward_max) }}円
                                    @endif
                                </div>
                            @endif

                            @php $tags = $model->style_tags ?? []; @endphp
                            @if(count($tags) > 0)
                                <div class="mt-1 flex flex-wrap gap-0.5">
                                    @foreach(array_slice($tags, 0, 2) as $tag)
                                        <span class="badge badge-secondary text-xs px-1 py-0">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($tags) > 2)
                                        <span class="text-xs text-secondary-500">+{{ count($tags) - 2 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ========== 高評価レビュー欄 ========== --}}
    @if(isset($highRatingReviews) && $highRatingReviews->count() > 0)
        <section class="section mb-16">
            <div class="section-header mb-8">
                <h2 class="section-title text-3xl sm:text-4xl">高評価レビュー</h2>
            </div>
            <div class="section-panel">
                <div class="section-panel-inner px-8 sm:px-10 lg:px-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($highRatingReviews as $review)
                            <div class="card card-hover">
                                <div class="card-body">
                                    {{-- 日付と評価ステータス --}}
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="text-sm text-secondary-600">
                                            {{ $review->created_at->format('Y.m.d') }}
                                        </span>
                                        <span class="badge badge-success">
                                            {{ $review->rating_label }}
                                        </span>
                                    </div>

                                    {{-- プロフィール画像とレビュワー名 --}}
                                    <div class="flex items-center gap-3 mb-3">
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
                                        <div class="w-12 h-12 rounded-full overflow-hidden bg-secondary-200 flex items-center justify-center flex-shrink-0">
                                            @if($profileImage)
                                                <img src="{{ Storage::url($profileImage) }}"
                                                     alt="{{ $reviewerName }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-secondary-900">
                                                @if($review->reviewer->role === 'painter')
                                                    クライアントの{{ $reviewerName }}さん
                                                @else
                                                    モデルの{{ $reviewerName }}さん
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- コメント --}}
                                    @if($review->comment)
                                        <div class="bg-secondary-100 rounded-lg p-3">
                                            <p class="text-sm text-secondary-700 leading-relaxed line-clamp-3">
                                                {{ $review->comment }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- インフォメーションと新着オファー --}}
    <div class="flex flex-row gap-8 mb-12">
        {{-- 左カラム: お知らせとプレスリリース（縦に並べる） --}}
        <div class="flex flex-col space-y-8 flex-1 min-w-0">
            {{-- INFORMATION / お知らせ --}}
            @if($informations->count() > 0)
                <section class="block">
                    <h2 class="text-xl font-bold text-secondary-900 mb-4">> INFORMATION / お知らせ</h2>
                    <div class="space-y-2">
                        @foreach($informations as $info)
                            <div>
                                <a href="{{ route('information.show', $info) }}" class="link-primary underline">
                                    {{ $info->published_at->format('Y.m.d') }} {{ $info->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('information.index', ['type' => 'information']) }}" class="link-primary text-sm">
                            すべて表示
                        </a>
                    </div>
                </section>
            @endif

            {{-- FASHION PRESS / プレスリリース --}}
            @if($pressReleases->count() > 0)
                <section>
                    <h2 class="text-xl font-bold text-secondary-900 mb-4">> FASHION PRESS / プレスリリース</h2>
                    <div class="space-y-2">
                        @foreach($pressReleases as $press)
                            <div>
                                <a href="{{ route('information.show', $press) }}" class="link-primary underline">
                                    {{ $press->published_at->format('Y年m月d日') }} {{ $press->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- 右カラム: 新着オファー（左側の高さに合わせる） --}}
        <div class="flex-1 min-w-0">
            @if($newJobOffers->count() > 0)
                <section>
                    <h2 class="text-xl font-bold text-secondary-900 mb-4">> JOB OFFER / 新着オファー</h2>
                    <div class="flex flex-col space-y-1.5">
                        @foreach($newJobOffers as $job)
                            <div>
                                <a href="{{ route('jobs.show', $job) }}" class="link-primary block">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-sm text-secondary-600 whitespace-nowrap">{{ $job->created_at->format('Y.m.d H:i') }}</span>
                                        <span class="text-secondary-900">{{ $job->title }}</span>
                                        <span class="text-secondary-500 whitespace-nowrap">({{ $job->applications()->count() }})</span>
                                    </div>
                                    @if($job->painter && $job->painter->painterProfile)
                                        <div class="text-xs text-secondary-600 mt-0.5">
                                            {{ $job->painter->painterProfile->display_name }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('jobs.index') }}" class="link-primary text-sm">
                            すべて表示
                        </a>
                    </div>
                </section>
            @endif
        </div>
    </div>

</div>
@endsection
