@extends('layouts.app')

@section('title', '届いた個別依頼')

@section('content')

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">届いた個別依頼</span>
        </div>
        <p class="page-header-subtitle">Direct Offers</p>
        <h1 class="page-header-title mt-2">届いた個別依頼</h1>
        <p class="text-secondary-500 text-sm mt-3">画家から直接届いた依頼に対して、受諾または辞退を選べます。</p>
    </div>
</div>

<div class="page space-y-10">

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

    {{-- 返答待ち --}}
    <section>
        <h2 class="font-display text-lg font-semibold text-secondary-900 mb-4">返答待ち <span class="text-secondary-400 text-sm">({{ $pendingOffers->count() }} 件)</span></h2>

        @if($pendingOffers->isEmpty())
            <div class="border border-dashed border-secondary-300 px-5 py-12 text-center">
                <p class="text-sm text-secondary-500">届いている個別依頼はありません。</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($pendingOffers as $offer)
                    @php
                        $painter = $offer->job->painter;
                        $painterProfile = $painter->painterProfile;
                        $painterName = $painterProfile?->display_name ?? $painter->name;
                    @endphp
                    <article class="bg-canvas-50 border border-secondary-200 rounded-xl overflow-hidden">
                        {{-- 画家情報 + 依頼概要 --}}
                        <div class="p-5 border-b border-secondary-100">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="avatar avatar-md border-2 border-primary-100 shrink-0">
                                    @if($painterProfile?->profile_image_path)
                                        <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterName }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-secondary-900 truncate">{{ $painterName }}</p>
                                    <p class="text-xs text-secondary-400">{{ $offer->created_at->diffForHumans() }}に届きました</p>
                                </div>
                            </div>

                            <h3 class="font-semibold text-secondary-900">{{ $offer->job->title }}</h3>
                            <p class="text-sm text-secondary-600 mt-1 line-clamp-3">{{ $offer->job->description }}</p>
                            <div class="flex flex-wrap gap-3 mt-3 text-xs text-secondary-500">
                                @if($offer->job->reward_amount)
                                    <span>報酬 ¥{{ number_format($offer->job->reward_amount) }}</span>
                                @endif
                                @if($offer->job->scheduled_date)
                                    <span>撮影日 {{ $offer->job->scheduled_date->format('Y/m/d') }}</span>
                                @endif
                                <span>{{ $offer->job->location_type === 'online' ? 'オンライン' : 'オフライン' }}</span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('jobs.show', $offer->job) }}" class="text-xs text-primary-600 hover:text-primary-700 underline">依頼の詳細を見る</a>
                            </div>
                        </div>

                        {{-- 画家からのメッセージ --}}
                        @if($offer->painter_message)
                            <div class="px-5 py-4 bg-secondary-50">
                                <p class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mb-1">画家からのメッセージ</p>
                                <p class="text-sm text-secondary-700 leading-relaxed whitespace-pre-line">{{ $offer->painter_message }}</p>
                            </div>
                        @endif

                        {{-- 受諾フォーム --}}
                        <details class="border-t border-secondary-100">
                            <summary class="px-5 py-3 cursor-pointer text-sm text-success-700 font-medium hover:bg-success-50 transition-colors">
                                受諾する
                            </summary>
                            <form action="{{ route('model.job-offers.accept', $offer) }}" method="POST" class="px-5 py-4 space-y-3">
                                @csrf
                                <textarea name="model_response" rows="3" maxlength="2000"
                                          placeholder="画家への一言（任意・最大 2000 文字）"
                                          class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900"></textarea>
                                <button type="submit" class="px-5 py-2 bg-success-600 text-white text-sm font-medium hover:bg-success-700 transition-colors">
                                    この依頼を受諾する
                                </button>
                            </form>
                        </details>

                        {{-- 辞退フォーム --}}
                        <details class="border-t border-secondary-100">
                            <summary class="px-5 py-3 cursor-pointer text-sm text-error-700 font-medium hover:bg-error-50 transition-colors">
                                辞退する
                            </summary>
                            <form action="{{ route('model.job-offers.decline', $offer) }}" method="POST" class="px-5 py-4 space-y-3">
                                @csrf
                                <p class="text-xs text-secondary-500 leading-relaxed">
                                    辞退の連絡は丁寧な定型文で画家へお伝えします。差し支えなければ補足のメッセージを添えられます（任意）。
                                </p>
                                <textarea name="model_response" rows="3" maxlength="2000"
                                          placeholder="補足メッセージ（任意・最大 2000 文字）"
                                          class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900"></textarea>
                                <button type="submit" class="px-5 py-2 bg-canvas-50 border border-error-500 text-error-600 text-sm font-medium hover:bg-error-50 transition-colors">
                                    辞退する
                                </button>
                            </form>
                        </details>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- 過去のオファー履歴 --}}
    @if($pastOffers->isNotEmpty())
        <section>
            <h2 class="font-display text-lg font-semibold text-secondary-900 mb-4">履歴 <span class="text-secondary-400 text-sm">({{ $pastOffers->count() }} 件)</span></h2>
            <ul class="space-y-3">
                @foreach($pastOffers as $offer)
                    @php
                        $painter = $offer->job->painter;
                        $painterName = $painter->painterProfile?->display_name ?? $painter->name;
                    @endphp
                    <li class="bg-canvas-50 border border-secondary-200 rounded-lg p-4 flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-semibold text-secondary-900 truncate">{{ $offer->job->title }}</p>
                            <p class="text-xs text-secondary-500 mt-1">{{ $painterName }} さんから</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full shrink-0
                                     {{ $offer->isAccepted() ? 'bg-success-50 text-success-700 border border-success-200' : 'bg-secondary-100 text-secondary-600 border border-secondary-200' }}">
                            {{ $offer->isAccepted() ? '受諾' : '辞退' }}
                            @if($offer->responded_at)
                                · {{ $offer->responded_at->format('Y/m/d') }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</div>

@endsection
