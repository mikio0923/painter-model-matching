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
        @if($reviewCount > 0)
            <div class="mt-3 flex items-center gap-2">
                <x-star-rating :rating="(int) round($reviewAvg)" size="sm" />
                <span class="text-sm font-medium text-secondary-800">{{ $reviewAvg }}</span>
                <span class="text-xs text-secondary-500">({{ $reviewCount }}件のレビュー)</span>
            </div>
        @endif
    </div>
</div>

<div class="page space-y-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- 左カラム：プロフィール --}}
        <aside class="lg:col-span-1 space-y-6">
            <div class="relative overflow-hidden bg-gradient-to-br from-primary-50 via-canvas-50 to-canvas-100 border border-primary-100 rounded-xl p-6 text-center">
                {{-- 装飾用の薄い円 --}}
                <span class="pointer-events-none absolute -top-10 -right-10 w-32 h-32 rounded-full bg-primary-100/40 blur-2xl"></span>
                <span class="pointer-events-none absolute -bottom-12 -left-10 w-32 h-32 rounded-full bg-warning-100/40 blur-2xl"></span>

                <div class="relative">
                    <div class="avatar avatar-xl mx-auto mb-4 border-4 border-canvas-50 shadow-md ring-2 ring-primary-200">
                        @if($painterProfile->profile_image_path)
                            <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterProfile->display_name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @endif
                    </div>
                    <h2 class="font-display text-xl font-bold text-secondary-900">{{ $painterProfile->display_name ?? $painter->name }}</h2>
                    <p class="text-[10px] text-primary-600 mt-1 uppercase tracking-[0.3em] font-medium">Painter</p>

                    @if($painterProfile->prefecture)
                        <p class="text-sm text-secondary-600 mt-3 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-secondary-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            {{ $painterProfile->prefecture }}
                        </p>
                    @endif

                    @if($painterProfile->accepts_offers)
                        <span class="inline-flex items-center gap-1 mt-3 px-3 py-1 text-xs bg-success-500 text-white rounded-full shadow-sm">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                            オファー受付中
                        </span>
                    @endif

                    {{-- 評価サマリー --}}
                    <div class="mt-5 pt-4 border-t border-primary-100/60">
                        @if($reviewCount > 0)
                            <div class="flex items-center justify-center gap-2">
                                <x-star-rating :rating="(int) round($reviewAvg)" />
                                <span class="text-base font-semibold text-secondary-900">{{ $reviewAvg }}</span>
                                <span class="text-xs text-secondary-500">/ 5</span>
                            </div>
                            <p class="text-xs text-secondary-500 mt-1">{{ $reviewCount }} 件のレビュー</p>
                        @else
                            <p class="text-xs text-secondary-400">まだ評価がありません</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ハイライト数値 --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-4 text-center">
                    <p class="text-2xl font-display font-semibold text-primary-700 tabular-nums">{{ $openJobsTotal }}</p>
                    <p class="text-[10px] text-secondary-500 uppercase tracking-[0.2em] mt-1">募集中</p>
                </div>
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-4 text-center">
                    <p class="text-2xl font-display font-semibold text-warning-700 tabular-nums">{{ $pastJobsTotal }}</p>
                    <p class="text-[10px] text-secondary-500 uppercase tracking-[0.2em] mt-1">実績</p>
                </div>
            </div>

            {{-- 基本情報 --}}
            <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6 space-y-3 text-sm">
                <h3 class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Profile</h3>
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
                           class="text-primary-600 hover:text-primary-700 font-medium truncate underline inline-flex items-center gap-1">
                            外部リンク
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </aside>

        {{-- 右カラム：詳細情報 --}}
        <div class="lg:col-span-2 space-y-6">
            @if($painterProfile->bio)
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-primary-500 rounded-full"></span>
                        自己紹介
                    </h3>
                    <p class="text-secondary-700 leading-relaxed whitespace-pre-line">{{ $painterProfile->bio }}</p>
                </div>
            @endif

            @if($painterProfile->experience)
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-warning-500 rounded-full"></span>
                        経歴・実績
                    </h3>
                    <p class="text-secondary-700 leading-relaxed whitespace-pre-line">{{ $painterProfile->experience }}</p>
                </div>
            @endif

            @if(!empty($painterProfile->art_styles) || !empty($painterProfile->specialties))
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6 space-y-4">
                    @if(!empty($painterProfile->art_styles))
                        <div>
                            <h3 class="font-display font-semibold text-secondary-900 mb-3 flex items-center gap-2">
                                <span class="w-1 h-5 bg-success-500 rounded-full"></span>
                                画風
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($painterProfile->art_styles as $style)
                                    <span class="px-3 py-1 text-xs bg-success-50 text-success-700 border border-success-200 rounded-full">{{ $style }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($painterProfile->specialties))
                        <div>
                            <h3 class="font-display font-semibold text-secondary-900 mb-3 flex items-center gap-2">
                                <span class="w-1 h-5 bg-primary-500 rounded-full"></span>
                                得意分野・スキル
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($painterProfile->specialties as $specialty)
                                    <span class="px-3 py-1 text-xs bg-primary-50 text-primary-700 border border-primary-200 rounded-full">{{ $specialty }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if(!empty($painterProfile->activity_regions))
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-warning-500 rounded-full"></span>
                        活動エリア
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($painterProfile->activity_regions as $region)
                            <span class="px-3 py-1 text-xs bg-warning-50 text-warning-700 border border-warning-200 rounded-full">{{ $region }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 募集中の依頼（最大3件） --}}
            @if($openJobs->isNotEmpty())
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display font-semibold text-secondary-900 flex items-center gap-2">
                            <span class="w-1 h-5 bg-primary-500 rounded-full"></span>
                            募集中の依頼
                            <span class="text-xs text-secondary-500 font-normal">（{{ $openJobsTotal }} 件中 {{ $openJobs->count() }} 件）</span>
                        </h3>
                    </div>
                    <ul class="space-y-2">
                        @foreach($openJobs as $job)
                            <li>
                                <a href="{{ route('jobs.show', $job) }}"
                                   class="block border border-secondary-200 hover:border-primary-300 hover:bg-primary-50/30 px-4 py-3 rounded-lg transition-colors">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-secondary-900 line-clamp-1">{{ $job->title }}</p>
                                            <p class="text-xs text-secondary-500 mt-1 line-clamp-2">{{ $job->description }}</p>
                                            <div class="flex flex-wrap gap-2 mt-2 text-[11px] text-secondary-500">
                                                @if($job->reward_amount)
                                                    <span>¥{{ number_format($job->reward_amount) }}</span>
                                                @endif
                                                @if($job->scheduled_date)
                                                    <span>{{ $job->scheduled_date->format('Y/n/j') }}</span>
                                                @endif
                                                <span>{{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}</span>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-secondary-300 group-hover:text-primary-500 mt-1 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 過去の完了依頼 + もっと見る --}}
            @if($pastJobs->isNotEmpty())
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-display font-semibold text-secondary-900 flex items-center gap-2">
                            <span class="w-1 h-5 bg-warning-500 rounded-full"></span>
                            これまでの実績
                            <span class="text-xs text-secondary-500 font-normal">（{{ $pastJobsTotal }} 件中 {{ $pastJobs->count() }} 件）</span>
                        </h3>
                    </div>
                    <ul class="space-y-2">
                        @foreach($pastJobs as $job)
                            <li>
                                <a href="{{ route('jobs.show', $job) }}"
                                   class="block border border-secondary-200 hover:border-warning-300 hover:bg-warning-50/30 px-4 py-3 rounded-lg transition-colors">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-secondary-900 line-clamp-1">{{ $job->title }}</p>
                                            <p class="text-xs text-secondary-500 mt-1 line-clamp-2">{{ $job->description }}</p>
                                            @if($job->scheduled_date)
                                                <p class="text-[10px] text-secondary-400 mt-1.5 uppercase tracking-wider">{{ $job->scheduled_date->format('Y / n / j') }} 実施</p>
                                            @endif
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 bg-warning-50 text-warning-700 border border-warning-200 rounded-full shrink-0">完了</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if($pastJobsTotal > $pastJobs->count())
                        <div class="mt-4 text-center">
                            <a href="{{ route('painters.jobs', $painterProfile) }}"
                               class="inline-flex items-center gap-1 px-5 py-2 border border-secondary-400 text-sm text-secondary-700 hover:bg-secondary-100 transition-colors rounded">
                                過去の依頼をすべて見る ({{ $pastJobsTotal }} 件)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- 最新レビュー（依頼名 + 依頼詳細リンク付き） --}}
            @if($latestReviews->isNotEmpty())
                <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                    <h3 class="font-display font-semibold text-secondary-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-5 bg-secondary-900 rounded-full"></span>
                        レビュー
                    </h3>
                    <ul class="space-y-4">
                        @foreach($latestReviews as $review)
                            <li class="border-b border-secondary-100 last:border-b-0 pb-4 last:pb-0">
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    <x-star-rating :rating="(int) $review->rating" size="sm" />
                                    <span class="text-xs text-secondary-500">
                                        {{ $review->reviewer->name ?? '退会済みユーザー' }} ・
                                        {{ $review->created_at->format('Y/n/j') }}
                                    </span>
                                </div>
                                @if($review->job)
                                    <p class="text-xs text-secondary-500 mb-2">
                                        対象依頼:
                                        <a href="{{ route('jobs.show', $review->job) }}"
                                           class="text-primary-700 hover:text-primary-800 underline font-medium">
                                            {{ $review->job->title }}
                                        </a>
                                    </p>
                                @endif
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
