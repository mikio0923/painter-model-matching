@extends('layouts.app')

@section('title', '応募者一覧')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('painter.jobs.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                依頼一覧
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">応募者</span>
        </div>
        <p class="page-header-subtitle">Applicants</p>
        <h1 class="page-header-title mt-2">{{ $job->title }}</h1>
        <p class="text-secondary-500 text-sm mt-3">応募者数 <span class="text-secondary-900 font-medium">{{ $applications->count() }}</span> 件</p>
    </div>
</div>

<div class="page">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Updated</p>
            {{ session('success') }}
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Applicants</p>
            <p class="text-secondary-500 text-sm">まだ応募がありません。</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach($applications as $application)
                @php
                    $statusInfo = match($application->status) {
                        'pending'  => ['label' => '未対応',   'text' => 'text-warning-700',   'border' => 'border-warning-500',   'bg' => 'bg-warning-50'],
                        'accepted' => ['label' => '採用済み', 'text' => 'text-success-700',   'border' => 'border-success-500',   'bg' => 'bg-success-50'],
                        'rejected' => ['label' => '辞退',     'text' => 'text-error-600',     'border' => 'border-error-500',     'bg' => 'bg-error-50'],
                        default    => ['label' => '—',        'text' => 'text-secondary-400', 'border' => 'border-secondary-300', 'bg' => 'bg-secondary-50'],
                    };
                @endphp

                <article class="border border-secondary-200 bg-canvas-50 hover:border-secondary-400 transition-colors duration-300">
                    <div class="px-5 sm:px-6 py-5">
                        <div class="flex items-start gap-4">
                            @if($application->model->modelProfile && $application->model->modelProfile->profile_image_path)
                                <img src="{{ Storage::url($application->model->modelProfile->profile_image_path) }}"
                                     alt="{{ $application->model->modelProfile->display_name }}"
                                     class="w-20 h-20 object-cover border border-secondary-200 shrink-0">
                            @else
                                <div class="w-20 h-20 bg-secondary-100 border border-secondary-200 flex items-center justify-center shrink-0">
                                    <svg class="w-8 h-8 text-secondary-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <h3 class="font-display text-lg font-semibold text-secondary-900 leading-snug min-w-0">
                                        @if($application->model->modelProfile)
                                            <a href="{{ route('models.show', $application->model->modelProfile) }}" class="hover:text-secondary-700 transition-colors">
                                                {{ $application->model->modelProfile->display_name }}
                                            </a>
                                        @else
                                            {{ $application->model->name }}
                                        @endif
                                    </h3>
                                    <span class="inline-flex items-center px-3 py-1 border {{ $statusInfo['border'] }} {{ $statusInfo['bg'] }} text-sm font-medium {{ $statusInfo['text'] }} shrink-0">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </div>

                                @if($application->model->modelProfile)
                                    <p class="text-xs text-secondary-500 mb-3">
                                        @if($application->model->modelProfile->prefecture)
                                            <span>{{ $application->model->modelProfile->prefecture }}</span>
                                        @endif
                                        @if($application->model->modelProfile->age)
                                            <span class="ml-2">{{ $application->model->modelProfile->age }} 歳</span>
                                        @endif
                                    </p>
                                @endif

                                @if($application->message)
                                    <div class="px-4 py-3 bg-secondary-50 border-l-2 border-secondary-300 text-sm text-secondary-700 leading-relaxed whitespace-pre-wrap">
                                        {{ $application->message }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-secondary-200 px-5 sm:px-6 py-3 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">
                        <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                            応募日 {{ $application->created_at->format('Y . n . j  H:i') }}
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @if($application->status === 'pending')
                                <form action="{{ route('painter.jobs.applications.reject', ['job' => $job, 'application' => $application]) }}" method="POST"
                                      onsubmit="return confirm('この応募を辞退（不採用）として通知します。よろしいですか？');">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-2 bg-canvas-50 border border-error-500 text-error-600 text-sm hover:bg-error-50 transition-colors duration-200">
                                        辞退する
                                    </button>
                                </form>
                                <form action="{{ route('painter.jobs.applications.accept', ['job' => $job, 'application' => $application]) }}" method="POST"
                                      onsubmit="return confirm('この応募を採用として通知します。よろしいですか？');">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-2 bg-success-600 text-canvas-50 border border-success-600 text-sm hover:bg-success-700 hover:border-success-700 transition-colors duration-200">
                                        採用する
                                    </button>
                                </form>
                            @endif

                            @if($application->status === 'accepted')
                                <a href="{{ route('messages.show', ['job' => $job, 'with' => $application->model_id]) }}"
                                   class="px-5 py-2 bg-success-600 text-canvas-50 border border-success-600 text-sm hover:bg-success-700 hover:border-success-700 transition-colors duration-200">
                                    メッセージ
                                </a>
                            @endif

                            @if($application->model->modelProfile)
                                <a href="{{ route('models.show', $application->model->modelProfile) }}"
                                   class="px-5 py-2 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-200">
                                    プロフィール
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
