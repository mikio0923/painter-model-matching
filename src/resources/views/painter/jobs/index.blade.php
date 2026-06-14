@extends('layouts.app')

@section('title', '依頼一覧')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">My Jobs</p>
        <h1 class="page-header-title mt-2">依頼一覧</h1>
        <p class="text-secondary-500 text-sm mt-3">作成した依頼の管理と応募者の確認ができます。</p>
    </div>
</div>

<div class="page space-y-6">

    {{-- 上部アクション --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3 flex-wrap">
            <p class="text-sm text-secondary-500">
                全 <span class="text-secondary-900 font-medium">{{ method_exists($jobs, 'total') ? $jobs->total() : $jobs->count() }}</span> 件
            </p>
            @php
                $filterLabel = match($currentStatus ?? null) {
                    'open'   => '募集中',
                    'closed' => '締切',
                    'done'   => '完了',
                    default  => null,
                };
            @endphp
            @if($filterLabel)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 border border-secondary-300 bg-secondary-50 text-xs text-secondary-700">
                    フィルタ：{{ $filterLabel }}
                    <a href="{{ route('painter.jobs.index') }}" class="text-secondary-500 hover:text-secondary-900" aria-label="フィルタを解除">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </span>
            @endif
        </div>
        <a href="{{ route('painter.jobs.create') }}"
           class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300 overflow-hidden">
            <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="text-sm font-medium tracking-wide">新しい依頼を作成</span>
        </a>
    </div>

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Updated</p>
            {{ session('success') }}
        </div>
    @endif

    @if($jobs->isEmpty())
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Jobs</p>
            <p class="text-secondary-500 text-sm mb-6">まだ依頼がありません。</p>
            <a href="{{ route('painter.jobs.create') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                最初の依頼を作成する
            </a>
        </div>
    @else
        <div class="space-y-5">
            @foreach($jobs as $job)
                @php
                    $statusInfo = match($job->status) {
                        'open'   => ['label' => '募集中', 'border' => 'border-success-500', 'bg' => 'bg-success-50',  'text' => 'text-success-700'],
                        'closed' => ['label' => '締切',   'border' => 'border-secondary-300', 'bg' => 'bg-secondary-50','text' => 'text-secondary-600'],
                        default  => ['label' => '完了',   'border' => 'border-primary-500', 'bg' => 'bg-primary-50',  'text' => 'text-primary-700'],
                    };
                @endphp

                <article class="border border-secondary-200 bg-canvas-50 hover:border-secondary-400 transition-colors duration-300">
                    <div class="px-5 sm:px-6 py-5">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <span class="inline-flex items-center px-3 py-1 border {{ $statusInfo['border'] }} {{ $statusInfo['bg'] }} text-xs font-medium {{ $statusInfo['text'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                    <span class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                        作成日 {{ $job->created_at->format('Y . n . j') }}
                                    </span>
                                </div>
                                <h2 class="font-display text-lg font-semibold text-secondary-900 leading-snug mb-1">
                                    <a href="{{ route('jobs.show', $job) }}" class="hover:text-secondary-700 transition-colors">
                                        {{ $job->title }}
                                    </a>
                                </h2>
                                <p class="text-sm text-secondary-600 line-clamp-2 leading-relaxed">
                                    {{ mb_strlen($job->description) > 120 ? mb_substr($job->description, 0, 120) . '…' : $job->description }}
                                </p>
                            </div>
                        </div>

                        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-2 text-xs pt-3 border-t border-secondary-200">
                            @if($job->category)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">カテゴリ</dt>
                                    <dd class="text-secondary-900 font-medium truncate">{{ $job->category }}</dd>
                                </div>
                            @endif
                            @if($job->reward_amount)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">報酬</dt>
                                    <dd class="text-secondary-900 font-medium">¥{{ number_format($job->reward_amount) }}<span class="text-secondary-400 font-normal">{{ $job->reward_unit === 'per_hour' ? '/時間' : '/回' }}</span></dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">場所</dt>
                                <dd class="text-secondary-900 font-medium">
                                    {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                </dd>
                            </div>
                            @if($job->apply_deadline)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">応募締切</dt>
                                    <dd class="text-secondary-900 font-medium">{{ $job->apply_deadline->format('Y/n/j') }}</dd>
                                </div>
                            @endif
                            @if($job->scheduled_date)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">撮影日</dt>
                                    <dd class="text-secondary-900 font-medium">{{ $job->scheduled_date->format('Y/n/j') }}</dd>
                                </div>
                            @endif
                            @if($job->transportation_fee)
                                <div>
                                    <dt class="text-secondary-400 uppercase tracking-wider text-[10px] mb-0.5">交通費</dt>
                                    <dd class="text-secondary-900 font-medium truncate">{{ $job->transportation_fee }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    {{-- アクションボタン --}}
                    <div class="border-t border-secondary-200 px-5 sm:px-6 py-3 flex flex-wrap gap-2 justify-end">
                        <a href="{{ route('jobs.show', $job) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs font-medium hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            詳細を見る
                        </a>
                        <a href="{{ route('painter.jobs.applications.index', $job) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-success-600 text-canvas-50 border border-success-600 text-xs font-medium hover:bg-success-700 hover:border-success-700 transition-colors duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            応募一覧
                            @if(($job->applications_count ?? $job->applications->count()) > 0)
                                <span class="ml-0.5 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 bg-canvas-50 text-success-700 text-[10px] font-semibold rounded-full">{{ $job->applications_count ?? $job->applications->count() }}</span>
                            @endif
                        </a>
                        <a href="{{ route('painter.jobs.edit', $job) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-canvas-50 border border-secondary-400 text-secondary-700 text-xs font-medium hover:bg-secondary-100 transition-colors duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            編集
                        </a>
                        <form method="POST" action="{{ route('painter.jobs.destroy', $job) }}"
                              onsubmit="return confirm('この依頼を削除しますか？この操作は取り消せません。')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-canvas-50 border border-error-500 text-error-600 text-xs font-medium hover:bg-error-50 transition-colors duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                削除
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        @if(method_exists($jobs, 'links'))
            <div class="mt-8">
                {{ $jobs->links('vendor.pagination.block-ten') }}
            </div>
        @endif
    @endif
</div>
@endsection
