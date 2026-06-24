@extends('layouts.app')

@section('title', 'エントリー履歴')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Applications</p>
        <h1 class="page-header-title mt-2">エントリー履歴</h1>
        <p class="text-secondary-500 text-sm mt-3">これまでに応募した依頼と現在のステータスを確認できます。</p>
    </div>
</div>

<div class="page-narrow"
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
        },
        matches(status) {
            if (this.filter === 'all') return true;
            if (this.filter === 'applying') return ['applying', 'accepted'].includes(status);
            if (this.filter === 'closed')   return ['closed', 'rejected'].includes(status);
            if (this.filter === 'done')     return status === 'done';
            return false;
        }
     }">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Updated</p>
            {{ session('success') }}
        </div>
    @endif

    {{-- フィルタ --}}
    @php
        $tabs = [
            'all'      => ['label' => 'すべて', 'count' => $counts['all']],
            'applying' => ['label' => '応募',   'count' => $counts['applying']],
            'closed'   => ['label' => '締切',   'count' => $counts['closed']],
            'done'     => ['label' => '完了',   'count' => $counts['done']],
        ];
    @endphp
    <div class="flex flex-wrap gap-2 mb-6">
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

    {{-- 0件メッセージ（フィルタで該当が無い時） --}}
    <div x-show="visibleCount === 0"
         class="border border-secondary-200 px-5 py-16 text-center">
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Applications</p>
        <p class="text-secondary-500 text-sm mb-6">
            <span x-show="counts.all === 0">まだ応募がありません。</span>
            <span x-show="counts.all > 0">該当する応募はありません。</span>
        </p>
        <a x-show="counts.all === 0" href="{{ route('jobs.index') }}" class="btn-museum-dark inline-flex">
            依頼を探す
        </a>
    </div>

    @if($applications->isNotEmpty())
        <div class="space-y-5">
            @foreach($applications as $application)
                @php
                    // display_status は controller で付与済（応募中 / 締切 / 完了 / 採用 / 辞退）
                    $statusInfo = match($application->display_status ?? '') {
                        'applying'  => ['label' => '応募中',     'text' => 'text-warning-700',   'border' => 'border-warning-500',   'bg' => 'bg-warning-50'],
                        'accepted'  => ['label' => '採用済み',   'text' => 'text-success-700',   'border' => 'border-success-500',   'bg' => 'bg-success-50'],
                        'closed'    => ['label' => '締切',       'text' => 'text-secondary-700', 'border' => 'border-secondary-400', 'bg' => 'bg-secondary-100'],
                        'done'      => ['label' => '完了',       'text' => 'text-secondary-700', 'border' => 'border-secondary-900', 'bg' => 'bg-canvas-50'],
                        'rejected'  => ['label' => '辞退',       'text' => 'text-error-600',     'border' => 'border-error-500',     'bg' => 'bg-error-50'],
                        default     => ['label' => '不明',       'text' => 'text-secondary-400', 'border' => 'border-secondary-300', 'bg' => 'bg-secondary-50'],
                    };
                @endphp

                <article class="border border-secondary-200 bg-canvas-50 hover:border-secondary-400 transition-colors duration-300"
                         x-show="matches('{{ $application->display_status ?? '' }}')"
                         x-cloak>
                    <div class="px-5 sm:px-6 py-5">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-1">
                                    {{ $application->job->painter->name ?? '退会済み画家' }}
                                </p>
                                <h2 class="font-display text-lg font-semibold text-secondary-900 leading-snug">
                                    <a href="{{ route('jobs.show', $application->job) }}" class="hover:text-secondary-700 transition-colors">
                                        {{ $application->job->title }}
                                    </a>
                                </h2>
                            </div>
                            <div class="text-right shrink-0 flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-3 py-1 border {{ $statusInfo['border'] }} {{ $statusInfo['bg'] }} text-sm font-medium {{ $statusInfo['text'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                                <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                    {{ $application->created_at->format('Y . n . j') }}
                                </p>
                            </div>
                        </div>

                        @if($application->message)
                            <div class="mb-4 px-4 py-3 bg-secondary-50 border-l-2 border-secondary-300 text-sm text-secondary-700 leading-relaxed whitespace-pre-wrap">{{ trim($application->message) }}</div>
                        @endif

                        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-2 text-xs">
                            @if($application->job->reward_amount)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">報酬</dt>
                                    <dd class="text-secondary-900 font-medium">¥{{ number_format($application->job->reward_amount) }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">場所</dt>
                                <dd class="text-secondary-900 font-medium">
                                    {{ $application->job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                </dd>
                            </div>
                            @if($application->job->scheduled_date)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">日程</dt>
                                    <dd class="text-secondary-900 font-medium">{{ $application->job->scheduled_date->format('Y/n/j') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="border-t border-secondary-200 px-5 sm:px-6 py-3 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-end">
                        @if($application->status === 'accepted')
                            <a href="{{ route('messages.show', ['job' => $application->job, 'with' => $application->job->painter_id]) }}"
                               class="px-5 py-2 bg-success-600 text-canvas-50 border border-success-600 text-sm hover:bg-success-700 hover:border-success-700 transition-colors duration-200 text-center">
                                メッセージ
                            </a>
                        @endif
                        <a href="{{ route('jobs.show', $application->job) }}"
                           class="px-5 py-2 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-200 text-center">
                            詳細を見る
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
