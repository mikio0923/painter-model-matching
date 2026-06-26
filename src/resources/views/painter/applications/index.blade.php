@extends('layouts.app')

@section('title', '受け取った応募')

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
            <span class="page-header-breadcrumb-current">受け取った応募</span>
        </div>
        <p class="page-header-subtitle">Applications</p>
        <h1 class="page-header-title mt-2">受け取った応募</h1>
        <p class="text-secondary-500 text-sm mt-3">応募者・コメント・依頼概要を一覧で確認し、その場で採用・辞退の連絡ができます。</p>
    </div>
</div>

<div class="page space-y-6"
     x-data="{
        filter: '{{ $filter }}',
        counts: {{ Js::from($counts) }},
        setFilter(f) {
            this.filter = f;
            const url = new URL(window.location.href);
            if (f === 'all') {
                url.searchParams.delete('filter');
            } else {
                url.searchParams.set('filter', f);
            }
            history.replaceState(null, '', url.toString());
        },
        get visibleCount() {
            return this.counts[this.filter] ?? 0;
        }
     }">

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-success-700 mb-1 font-medium">完了</p>
            {{ session('success') }}
        </div>
    @endif

    {{-- フィルタ --}}
    @php
        $tabs = [
            'all'      => ['label' => 'すべて',  'count' => $counts['all']],
            'pending'  => ['label' => '未対応',  'count' => $counts['pending']],
            'accepted' => ['label' => '採用',    'count' => $counts['accepted']],
            'rejected' => ['label' => '辞退',    'count' => $counts['rejected']],
        ];
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach($tabs as $key => $t)
            <button type="button" @click="setFilter('{{ $key }}')"
                    :class="filter === '{{ $key }}'
                        ? 'bg-secondary-900 text-canvas-50 border-secondary-900'
                        : 'bg-canvas-50 text-secondary-700 border-secondary-300 hover:bg-secondary-100'"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm border transition-colors">
                {{ $t['label'] }}
                <span class="text-xs"
                      :class="filter === '{{ $key }}' ? 'text-canvas-50/70' : 'text-secondary-400'">
                    {{ $t['count'] }}
                </span>
            </button>
        @endforeach
    </div>

    {{-- 0件メッセージ（フィルタ条件で何も該当しない時） --}}
    <div x-show="visibleCount === 0"
         class="border border-dashed border-secondary-300 px-5 py-12 text-center">
        <p class="text-sm text-secondary-500">該当する応募はありません。</p>
    </div>

    @if($applications->isNotEmpty())
        <div class="space-y-4">
            @foreach($applications as $app)
                @php
                    $model = $app->model;
                    $modelProfile = $model?->modelProfile;
                    $modelName = $modelProfile?->display_name ?? $model?->name ?? '退会済みユーザー';
                    $modelImage = $modelProfile?->profile_image_path;
                @endphp
                <article class="bg-canvas-50 border border-secondary-200 rounded-xl overflow-hidden"
                         x-show="filter === 'all' || filter === '{{ $app->status }}'"
                         x-cloak>
                    {{-- ヘッダー: 応募者 + ステータス --}}
                    <div class="px-5 py-4 border-b border-secondary-100 flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($modelProfile)
                                <a href="{{ route('models.show', $modelProfile) }}"
                                   class="avatar avatar-md border-2 border-primary-100 shrink-0 hover:border-primary-300 transition-colors">
                                    @if($modelImage)
                                        <img src="{{ Storage::url($modelImage) }}" alt="{{ $modelName }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    @endif
                                </a>
                            @else
                                <div class="avatar avatar-md border-2 border-secondary-200 shrink-0">
                                    <svg class="w-5 h-5 text-secondary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                @if($modelProfile)
                                    <a href="{{ route('models.show', $modelProfile) }}" class="font-semibold text-secondary-900 truncate hover:text-primary-600 transition-colors">
                                        {{ $modelName }}
                                    </a>
                                @else
                                    <span class="font-semibold text-secondary-900 truncate">{{ $modelName }}</span>
                                @endif
                                <p class="text-xs text-secondary-400 mt-0.5">
                                    {{ $app->created_at->format('Y/n/j H:i') }} 応募
                                    @if($modelProfile?->prefecture)・{{ $modelProfile->prefecture }}@endif
                                </p>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full shrink-0
                                     @if($app->status === 'accepted') bg-success-50 text-success-700 border border-success-200
                                     @elseif($app->status === 'rejected') bg-secondary-100 text-secondary-600 border border-secondary-200
                                     @else bg-warning-50 text-warning-700 border border-warning-200
                                     @endif">
                            @if($app->status === 'accepted') 採用
                            @elseif($app->status === 'rejected') 辞退
                            @else 未対応
                            @endif
                        </span>
                    </div>

                    {{-- 依頼概要 --}}
                    <div class="px-5 py-4 bg-secondary-50/50 border-b border-secondary-100">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mb-1">依頼</p>
                        <a href="{{ route('jobs.show', $app->job) }}" class="block hover:text-primary-600 transition-colors">
                            <p class="font-medium text-secondary-900 line-clamp-1">{{ $app->job->title }}</p>
                        </a>
                        <div class="flex flex-wrap gap-3 mt-2 text-xs text-secondary-500">
                            @if($app->job->reward_amount)
                                <span>¥{{ number_format($app->job->reward_amount) }}</span>
                            @endif
                            @if($app->job->scheduled_date)
                                <span>{{ $app->job->scheduled_date->format('Y/n/j') }} 撮影</span>
                            @endif
                            <span>{{ $app->job->location_type === 'online' ? 'オンライン' : 'オフライン' }}</span>
                        </div>
                    </div>

                    {{-- 応募コメント --}}
                    @if($app->message)
                        <div class="px-5 py-4 border-b border-secondary-100">
                            <p class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mb-1">応募コメント</p>
                            <p class="text-sm text-secondary-700 leading-relaxed whitespace-pre-line">{{ $app->message }}</p>
                        </div>
                    @endif

                    {{-- アクション --}}
                    <div class="px-5 py-3 flex flex-wrap items-center justify-end gap-2">
                        @if($app->status === 'pending')
                            <form action="{{ route('painter.jobs.applications.reject', [$app->job, $app]) }}" method="POST"
                                  onsubmit="return confirm('この応募を辞退（不採用）として通知します。よろしいですか？');">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 text-sm bg-canvas-50 border border-error-500 text-error-600 hover:bg-error-50 transition-colors">
                                    辞退する
                                </button>
                            </form>
                            @if($app->job_is_full ?? false)
                                <span class="px-4 py-2 text-sm bg-secondary-100 border border-secondary-300 text-secondary-500 cursor-not-allowed">
                                    既に採用済みのモデルがいます
                                </span>
                            @else
                                <form action="{{ route('painter.jobs.applications.accept', [$app->job, $app]) }}" method="POST"
                                      onsubmit="return confirm('この応募を採用として通知します。よろしいですか？');">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 text-sm bg-success-600 text-white border border-success-600 hover:bg-success-700 transition-colors">
                                        採用する
                                    </button>
                                </form>
                            @endif
                        @else
                            <span class="text-xs text-secondary-400">
                                {{ $app->status === 'accepted' ? '採用通知済み' : '辞退通知済み' }}
                            </span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

@endsection
