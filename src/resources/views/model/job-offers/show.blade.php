@extends('layouts.app')

@section('title', '個別依頼の詳細')

@section('content')

@php
    $job = $offer->job;
    $painter = $job->painter;
    $painterProfile = $painter->painterProfile;
    $painterName = $painterProfile?->display_name ?? $painter->name;
@endphp

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('model.job-offers.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                届いた個別依頼
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">{{ $job->title }}</span>
        </div>
        <p class="page-header-subtitle">Direct Offer</p>
        <h1 class="page-header-title mt-2">{{ $job->title }}</h1>
        <p class="text-secondary-500 text-sm mt-3">
            {{ $painterName }} さんからの個別依頼
            （{{ $offer->created_at->format('Y年n月j日 H:i') }} 受信）
        </p>
    </div>
</div>

<div class="page space-y-8">

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-success-700 mb-1 font-medium">完了</p>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-error-700 mb-1 font-medium">エラー</p>
            {{ session('error') }}
        </div>
    @endif

    {{-- ステータス --}}
    <div>
        @if($offer->isPending())
            <span class="inline-block px-3 py-1 text-xs bg-warning-50 text-warning-700 border border-warning-200 rounded-full">返答待ち</span>
        @elseif($offer->isAccepted())
            <span class="inline-block px-3 py-1 text-xs bg-success-50 text-success-700 border border-success-200 rounded-full">受諾済み（{{ $offer->responded_at?->format('Y/m/d') }}）</span>
        @elseif($offer->isDeclined())
            <span class="inline-block px-3 py-1 text-xs bg-secondary-100 text-secondary-600 border border-secondary-200 rounded-full">辞退済み（{{ $offer->responded_at?->format('Y/m/d') }}）</span>
        @endif
    </div>

    {{-- 画家情報 --}}
    <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
        <h2 class="font-display text-base font-semibold text-secondary-900 mb-4">依頼主</h2>
        <div class="flex items-center gap-4">
            <a href="{{ route('painters.show', $painterProfile) }}"
               class="avatar avatar-lg border-2 border-primary-100 shrink-0 hover:border-primary-300 transition-colors">
                @if($painterProfile?->profile_image_path)
                    <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterName }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-7 h-7 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @endif
            </a>
            <div class="min-w-0">
                <a href="{{ route('painters.show', $painterProfile) }}"
                   class="font-bold text-secondary-900 truncate hover:text-primary-600 transition-colors">
                    {{ $painterName }}
                </a>
                <p class="text-xs text-secondary-400 mt-0.5">画家</p>
                @if($painterProfile?->bio)
                    <p class="text-sm text-secondary-600 mt-2 line-clamp-2">{{ $painterProfile->bio }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- 依頼内容 --}}
    <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6 space-y-4">
        <h2 class="font-display text-base font-semibold text-secondary-900">依頼内容</h2>

        <p class="text-secondary-700 leading-relaxed whitespace-pre-line">{{ $job->description }}</p>

        <dl class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-secondary-100 text-sm">
            @if($job->reward_amount)
                <div>
                    <dt class="text-xs text-secondary-400 uppercase tracking-wider mb-1">報酬</dt>
                    <dd class="text-secondary-800 font-medium">
                        ¥{{ number_format($job->reward_amount) }}<span class="text-xs text-secondary-400 font-normal">{{ $job->reward_unit === 'per_hour' ? '/時間' : '/回' }}</span>
                    </dd>
                </div>
            @endif
            @if($job->scheduled_date)
                <div>
                    <dt class="text-xs text-secondary-400 uppercase tracking-wider mb-1">撮影日</dt>
                    <dd class="text-secondary-800 font-medium">{{ $job->scheduled_date->format('Y/m/d') }}</dd>
                </div>
            @endif
            @if($job->apply_deadline)
                <div>
                    <dt class="text-xs text-secondary-400 uppercase tracking-wider mb-1">応募締切</dt>
                    <dd class="text-secondary-800 font-medium">{{ $job->apply_deadline->format('Y/m/d') }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-xs text-secondary-400 uppercase tracking-wider mb-1">場所</dt>
                <dd class="text-secondary-800 font-medium">
                    {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                    @if($job->prefecture)（{{ $job->prefecture }}）@endif
                </dd>
            </div>
            @if($job->target)
                <div>
                    <dt class="text-xs text-secondary-400 uppercase tracking-wider mb-1">対象</dt>
                    <dd class="text-secondary-800 font-medium">{{ $job->target }}</dd>
                </div>
            @endif
            @if($job->usage_purpose)
                <div>
                    <dt class="text-xs text-secondary-400 uppercase tracking-wider mb-1">使用目的</dt>
                    <dd class="text-secondary-800 font-medium">{{ $job->usage_purpose }}</dd>
                </div>
            @endif
        </dl>
    </section>

    {{-- 画家からのメッセージ --}}
    @if($offer->painter_message)
        <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
            <h2 class="font-display text-base font-semibold text-secondary-900 mb-3">画家からのメッセージ</h2>
            <p class="text-sm text-secondary-700 leading-relaxed whitespace-pre-line">{{ $offer->painter_message }}</p>
        </section>
    @endif

    {{-- アクション or 既存レスポンス --}}
    @if($offer->isPending())
        <section class="bg-canvas-50 border border-secondary-200 rounded-xl overflow-hidden">
            <details class="border-b border-secondary-100">
                <summary class="px-5 py-4 cursor-pointer text-base font-medium text-success-700 hover:bg-success-50 transition-colors">
                    この依頼を受諾する
                </summary>
                <form action="{{ route('model.job-offers.accept', $offer) }}" method="POST" class="px-5 py-5 space-y-3">
                    @csrf
                    <textarea name="model_response" rows="4" maxlength="2000"
                              placeholder="画家への一言（任意・最大 2000 文字）"
                              class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm leading-relaxed focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900"></textarea>
                    <button type="submit" class="px-6 py-2.5 bg-success-600 text-white text-sm font-medium hover:bg-success-700 transition-colors">
                        受諾を確定する
                    </button>
                </form>
            </details>

            <details>
                <summary class="px-5 py-4 cursor-pointer text-base font-medium text-error-700 hover:bg-error-50 transition-colors">
                    この依頼を辞退する
                </summary>
                <form action="{{ route('model.job-offers.decline', $offer) }}" method="POST" class="px-5 py-5 space-y-3">
                    @csrf
                    <p class="text-xs text-secondary-500 leading-relaxed">
                        辞退の連絡は丁寧な定型文で画家へお伝えします。差し支えなければ補足のメッセージを添えられます（任意）。
                    </p>
                    <textarea name="model_response" rows="4" maxlength="2000"
                              placeholder="補足メッセージ（任意・最大 2000 文字）"
                              class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm leading-relaxed focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900"></textarea>
                    <button type="submit" class="px-6 py-2.5 bg-white border border-error-500 text-error-600 text-sm font-medium hover:bg-error-50 transition-colors">
                        辞退を確定する
                    </button>
                </form>
            </details>
        </section>
    @else
        @if($offer->model_response)
            <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
                <h2 class="font-display text-base font-semibold text-secondary-900 mb-3">あなたの返答</h2>
                <p class="text-sm text-secondary-700 leading-relaxed whitespace-pre-line">{{ $offer->model_response }}</p>
            </section>
        @endif
    @endif

    <div class="border-t border-secondary-200 pt-6">
        <a href="{{ route('model.job-offers.index') }}"
           class="inline-flex items-center gap-2 text-sm text-secondary-500 hover:text-secondary-900 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            届いた個別依頼の一覧に戻る
        </a>
    </div>
</div>

@endsection
