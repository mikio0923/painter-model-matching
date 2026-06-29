@extends('layouts.app')

@section('title', ($painterProfile->display_name ?? $painter->name) . ' の過去の依頼')

@section('content')

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('painters.show', $painterProfile) }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ $painterProfile->display_name ?? $painter->name }}
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">過去の依頼</span>
        </div>
        <p class="page-header-subtitle">Past Jobs</p>
        <h1 class="page-header-title mt-2">過去の依頼</h1>
        <p class="text-secondary-500 text-sm mt-3">{{ $painterProfile->display_name ?? $painter->name }} さんがこれまでに完了させた依頼の一覧です。</p>
    </div>
</div>

<div class="page space-y-6">
    @if($jobs->isEmpty())
        <div class="border border-dashed border-secondary-300 px-5 py-16 text-center">
            <p class="text-sm text-secondary-500">まだ完了した依頼がありません。</p>
        </div>
    @else
        <ul class="space-y-3">
            @foreach($jobs as $job)
                <li>
                    <a href="{{ route('jobs.show', $job) }}"
                       class="block bg-canvas-50 border border-secondary-200 hover:border-warning-300 hover:bg-warning-50/30 rounded-lg p-5 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-secondary-900 line-clamp-1">{{ $job->title }}</p>
                                <p class="text-sm text-secondary-600 mt-1 line-clamp-2">{{ $job->description }}</p>
                                <div class="flex flex-wrap gap-3 mt-3 text-xs text-secondary-500">
                                    @if($job->reward_amount)
                                        <span>報酬 ¥{{ number_format($job->reward_amount) }}</span>
                                    @endif
                                    @if($job->scheduled_date)
                                        <span>{{ $job->scheduled_date->format('Y/n/j') }} 実施</span>
                                    @endif
                                    <span>{{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}</span>
                                    @if($job->prefecture)<span>{{ $job->prefecture }}</span>@endif
                                </div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 bg-warning-50 text-warning-700 border border-warning-200 rounded-full shrink-0">完了</span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="flex justify-center">
            {{ $jobs->links() }}
        </div>
    @endif

    <div class="border-t border-secondary-200 pt-6">
        <a href="{{ route('painters.show', $painterProfile) }}"
           class="inline-flex items-center gap-2 text-sm text-secondary-500 hover:text-secondary-900 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            画家プロフィールに戻る
        </a>
    </div>
</div>

@endsection
