@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.jobs.index') }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Jobs
        </a>
        <span class="text-secondary-300">/</span>
        <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-700">Detail</span>
    </div>
    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Job Detail</p>
    <h1 class="font-display text-2xl font-semibold text-secondary-900">{{ $job->title }}</h1>
</div>

@php
    $statusClass = match($job->status) {
        'open'      => 'text-success-700',
        'completed' => 'text-secondary-500',
        default     => 'text-error-600',
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- 基本情報 --}}
    <div class="border border-secondary-200 bg-canvas-50">
        <div class="px-5 py-3 border-b border-secondary-200">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Basic Info</p>
            <h2 class="font-display text-base font-semibold text-secondary-900">基本情報</h2>
        </div>
        <dl class="divide-y divide-secondary-200">
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">ID</dt>
                <dd class="text-sm text-secondary-900">{{ $job->id }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Title</dt>
                <dd class="text-sm text-secondary-900">{{ $job->title }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">説明</dt>
                <dd class="text-sm text-secondary-900 whitespace-pre-wrap leading-relaxed">{{ $job->description }}</dd>
            </div>
            @if($job->category)
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Category</dt>
                <dd class="text-sm text-secondary-900">{{ $job->category }}</dd>
            </div>
            @endif
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Reward</dt>
                <dd class="text-sm text-secondary-900">¥{{ number_format($job->reward_amount) }} {{ $job->reward_unit }}</dd>
            </div>
            @if($job->transportation_fee)
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">交通費</dt>
                <dd class="text-sm text-secondary-900">{{ $job->transportation_fee }}</dd>
            </div>
            @endif
            @if($job->costume_provided)
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">衣装</dt>
                <dd class="text-sm text-secondary-900">{{ $job->costume_provided }}</dd>
            </div>
            @endif
            @if($job->target)
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">募集対象</dt>
                <dd class="text-sm text-secondary-900">{{ $job->target }}</dd>
            </div>
            @endif
            @if($job->recruitment_number)
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">募集人数</dt>
                <dd class="text-sm text-secondary-900">{{ number_format($job->recruitment_number) }} 名</dd>
            </div>
            @endif
            @if($job->location_type === 'offline' && ($job->prefecture || $job->city || $job->address))
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">場所</dt>
                <dd class="text-sm text-secondary-900">
                    @if($job->prefecture){{ $job->prefecture }}@endif
                    @if($job->city) {{ $job->city }}@endif
                    @if($job->address)
                        <div class="mt-1">{{ $job->address }}</div>
                    @endif
                </dd>
            </div>
            @endif
            @if($job->access)
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Access</dt>
                <dd class="text-sm text-secondary-900 whitespace-pre-wrap leading-relaxed">{{ $job->access }}</dd>
            </div>
            @endif
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Status</dt>
                <dd class="text-[10px] tracking-[0.25em] uppercase {{ $statusClass }}">● {{ $job->status_label }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">投稿者</dt>
                <dd class="text-sm text-secondary-900">{{ $job->painter->name }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">投稿日</dt>
                <dd class="text-sm text-secondary-900">{{ $job->created_at->format('Y年n月j日 H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- 応募情報 --}}
    <div class="border border-secondary-200 bg-canvas-50">
        <div class="px-5 py-3 border-b border-secondary-200">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Applications</p>
            <h2 class="font-display text-base font-semibold text-secondary-900">応募情報</h2>
        </div>
        <div class="px-5 py-4 border-b border-secondary-200">
            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">Total</p>
            <p class="font-display text-2xl text-secondary-900 mt-1">{{ $job->applications->count() }}</p>
        </div>
        @if($job->applications->count() > 0)
            <div class="divide-y divide-secondary-200">
                @foreach($job->applications as $application)
                    @php
                        $appStatusClass = match($application->status) {
                            'accepted' => 'text-success-700',
                            'rejected' => 'text-error-600',
                            default    => 'text-warning-700',
                        };
                    @endphp
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-medium text-secondary-900">{{ $application->model->name }}</span>
                            <span class="text-[10px] tracking-[0.25em] uppercase {{ $appStatusClass }}">● {{ $application->status_label }}</span>
                        </div>
                        <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400 mt-1">
                            {{ $application->created_at->format('Y . n . j  H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-5 py-12 text-center">
                <p class="text-secondary-500 text-sm">応募がありません。</p>
            </div>
        @endif
    </div>
</div>

<div class="flex justify-end">
    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST"
          onsubmit="return confirm('本当にこの依頼を削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-6 py-2.5 border border-error-500 text-error-600 text-xs uppercase tracking-[0.25em] hover:bg-error-50 transition-colors duration-200">
            Delete Job
        </button>
    </form>
</div>
@endsection
