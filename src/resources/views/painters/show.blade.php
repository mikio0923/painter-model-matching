@extends('layouts.app')

@section('title', ($painterProfile->display_name ?? $painter->name) . ' | 画家プロフィール')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('home') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                ホーム
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">画家プロフィール</span>
        </div>
        <p class="page-header-subtitle">Painter</p>
        <h1 class="page-header-title mt-2">{{ $painterProfile->display_name ?? $painter->name }}</h1>
    </div>
</div>

<div class="page space-y-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- 左カラム：プロフィール --}}
        <aside class="lg:col-span-1 space-y-6">
            <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6 text-center">
                <div class="avatar avatar-xl mx-auto mb-4 border-2 border-primary-100">
                    @if($painterProfile->profile_image_path)
                        <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterProfile->display_name }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @endif
                </div>
                <h2 class="font-display text-lg font-bold text-secondary-900">{{ $painterProfile->display_name ?? $painter->name }}</h2>
                <p class="text-xs text-secondary-400 mt-1 uppercase tracking-wider">画家</p>

                @if($painterProfile->prefecture)
                    <p class="text-sm text-secondary-600 mt-3">{{ $painterProfile->prefecture }}</p>
                @endif

                @if($painterProfile->accepts_offers)
                    <span class="inline-block mt-3 px-3 py-1 text-xs bg-success-50 text-success-700 border border-success-200 rounded-full">オファー受付中</span>
                @endif

                {{-- 評価サマリー --}}
                <div class="mt-5 pt-4 border-t border-secondary-100">
                    @if($reviewCount > 0)
                        <div class="flex items-center justify-center gap-2">
                            <x-star-rating :rating="(int) round($reviewAvg)" />
                            <span class="text-sm font-medium text-secondary-800">{{ $reviewAvg }}</span>
                            <span class="text-xs text-secondary-500">({{ $reviewCount }}件)</span>
                        </div>
                    @else
                        <p class="text-xs text-secondary-400">まだ評価がありません</p>
                    @endif
                </div>
            </div>

            {{-- 基本情報 --}}
            <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6 space-y-3 text-sm">
                @if($painterProfile->years_active)
                    <div class="flex justify-between">
                        <span class="text-secondary-400">活動年数</span>
                        <span class="text-secondary-800 font-medium">{{ $painterProfile->years_active }} 年</span>
                    </div>
                @endif
                @if($painterProfile->gender)
                    <div class="flex justify-between">
                        <span class="text-secondary-400">性別</span>
                        <span class="text-secondary-800 font-medium">{{ $painterProfile->gender }}</span>
                    </div>
                @endif
                @if($painterProfile->portfolio_url)
                    <div class="flex justify-between gap-2">
                        <span class="text-secondary-400 shrink-0">ポートフォリオ</span>
                        <a href="{{ $painterProfile->portfolio_url }}" target="_blank" rel="noopener noreferrer"
                           class="text-primary-600 hover:text-primary-700 font-medium truncate underline">
                            外部リンク
                        </a>
                    </div>
                @endif
            </div>
        </aside>

        {{-- 右カラム：詳細情報 --}}
        <div class="lg:col-span-2 space-y-6">
            @if($painterProfile->bio)
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-3">自己紹介</h3>
                    <p class="text-secondary-700 leading-relaxed whitespace-pre-line">{{ $painterProfile->bio }}</p>
                </div>
            @endif

            @if($painterProfile->experience)
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-3">経歴・実績</h3>
                    <p class="text-secondary-700 leading-relaxed whitespace-pre-line">{{ $painterProfile->experience }}</p>
                </div>
            @endif

            @if(!empty($painterProfile->art_styles) || !empty($painterProfile->specialties))
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6 space-y-4">
                    @if(!empty($painterProfile->art_styles))
                        <div>
                            <h3 class="font-display font-semibold text-secondary-900 mb-2">画風</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($painterProfile->art_styles as $style)
                                    <span class="px-3 py-1 text-xs bg-secondary-100 text-secondary-700 rounded-full">{{ $style }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($painterProfile->specialties))
                        <div>
                            <h3 class="font-display font-semibold text-secondary-900 mb-2">得意分野</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($painterProfile->specialties as $specialty)
                                    <span class="px-3 py-1 text-xs bg-secondary-100 text-secondary-700 rounded-full">{{ $specialty }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if(!empty($painterProfile->activity_regions))
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-2">活動エリア</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($painterProfile->activity_regions as $region)
                            <span class="px-3 py-1 text-xs bg-secondary-100 text-secondary-700 rounded-full">{{ $region }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 募集中の依頼 --}}
            @if($openJobs->isNotEmpty())
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-4">募集中の依頼</h3>
                    <ul class="divide-y divide-secondary-100">
                        @foreach($openJobs as $job)
                            <li class="py-3">
                                <a href="{{ route('jobs.show', $job) }}" class="block hover:bg-secondary-50 -mx-3 px-3 py-2 rounded transition-colors">
                                    <p class="font-medium text-secondary-900 line-clamp-1">{{ $job->title }}</p>
                                    <p class="text-xs text-secondary-500 mt-1 line-clamp-2">{{ $job->description }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 過去の完了依頼（実績） --}}
            @if($pastJobs->isNotEmpty())
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-4">これまでの実績</h3>
                    <ul class="divide-y divide-secondary-100">
                        @foreach($pastJobs as $job)
                            <li class="py-3">
                                <a href="{{ route('jobs.show', $job) }}" class="block hover:bg-secondary-50 -mx-3 px-3 py-2 rounded transition-colors">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-secondary-900 line-clamp-1">{{ $job->title }}</p>
                                            <p class="text-xs text-secondary-500 mt-1 line-clamp-2">{{ $job->description }}</p>
                                            @if($job->scheduled_date)
                                                <p class="text-[10px] text-secondary-400 mt-1.5 uppercase tracking-wider">{{ $job->scheduled_date->format('Y / n / j') }} 実施</p>
                                            @endif
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 bg-secondary-100 text-secondary-600 border border-secondary-200 rounded-full shrink-0">完了</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 最新レビュー --}}
            @if($latestReviews->isNotEmpty())
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-4">レビュー</h3>
                    <ul class="space-y-4">
                        @foreach($latestReviews as $review)
                            <li class="border-b border-secondary-100 last:border-b-0 pb-4 last:pb-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <x-star-rating :rating="(int) $review->rating" size="sm" />
                                    <span class="text-xs text-secondary-500">
                                        {{ $review->reviewer->name ?? '退会済みユーザー' }} ・
                                        {{ $review->created_at->format('Y/n/j') }}
                                    </span>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-secondary-700 leading-relaxed whitespace-pre-line">{{ $review->comment }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
