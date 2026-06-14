@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-secondary-900">ダッシュボード</h1>
</div>

{{-- 要対応アラート --}}
@php
    $alerts = [];
    // 本人確認は一旦停止
    // if (($stats['pending_identity_verifications'] ?? 0) > 0) {
    //     $alerts[] = [
    //         'type' => 'identity',
    //         'count' => $stats['pending_identity_verifications'],
    //         'label' => '本人確認の審査待ち',
    //         'route' => route('admin.identity-verifications.index'),
    //         'color' => 'bg-warning-50 border-warning-200 text-warning-800',
    //     ];
    // }
    if (($stats['unread_contacts'] ?? 0) > 0) {
        $alerts[] = [
            'type' => 'contact',
            'count' => $stats['unread_contacts'],
            'label' => '未読のお問い合わせ',
            'route' => route('admin.contacts.index'),
            'color' => 'bg-error-50 border-error-200 text-error-800',
        ];
    }
@endphp
@if(count($alerts) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        @foreach($alerts as $alert)
            <a href="{{ $alert['route'] }}" class="block {{ $alert['color'] }} border rounded-xl p-4 hover:shadow-card transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold">{{ $alert['label'] }}</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($alert['count']) }} 件</p>
                    </div>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
@endif

{{-- 統計カード --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">総ユーザー数</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['total_users']) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                <span>モデル: {{ number_format($stats['total_models']) }}</span>
                <span class="mx-2">|</span>
                <span>画家: {{ number_format($stats['total_painters']) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">依頼数</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['total_jobs']) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                公開中: {{ number_format($stats['open_jobs']) }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">応募数</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['total_applications']) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                未対応: {{ number_format($stats['pending_applications']) }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">お問い合わせ</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['total_contacts']) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                未読: {{ number_format($stats['unread_contacts']) }}
            </div>
        </div>
    </div>

    {{-- 本人確認カード（一旦停止）
    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">本人確認</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['pending_identity_verifications'] ?? 0) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                審査待ち
            </div>
        </div>
    </div>
    --}}

    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">レビュー</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['total_reviews']) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                投稿数
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="text-sm font-medium text-secondary-600 mb-2">メッセージ</h3>
            <p class="text-3xl font-bold text-secondary-900">{{ number_format($stats['total_messages']) }}</p>
            <div class="mt-2 text-sm text-secondary-500">
                送信数
            </div>
        </div>
    </div>
</div>

{{-- 本人確認 審査待ち（一旦停止）
@if($recentVerifications->count() > 0)
<div class="card mb-8">
    <div class="card-header flex items-center justify-between">
        <h2 class="text-lg font-semibold text-secondary-900">本人確認 審査待ち</h2>
        <a href="{{ route('admin.identity-verifications.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
            すべて見る →
        </a>
    </div>
    <div class="card-body">
        <div class="space-y-3">
            @foreach($recentVerifications as $v)
                <a href="{{ route('admin.identity-verifications.show', $v) }}"
                   class="flex items-center justify-between border-b border-secondary-200 pb-3 last:border-0 last:pb-0 hover:bg-secondary-50 -mx-2 px-2 py-1 rounded">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-secondary-900 truncate">{{ $v->user->name }}</p>
                        <p class="text-xs text-secondary-500">
                            {{ $v->document_type_label }} ・ {{ $v->created_at->format('Y/m/d H:i') }}
                        </p>
                    </div>
                    <span class="badge badge-warning shrink-0">{{ $v->status_label }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif
--}}

{{-- 最近のアクティビティ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- 最近の依頼 --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">最近の依頼</h2>
        </div>
        <div class="card-body">
            @if($recentJobs->count() > 0)
                <div class="space-y-3">
                    @foreach($recentJobs as $job)
                        <div class="border-b border-secondary-200 pb-3 last:border-0 last:pb-0">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                {{ $job->title }}
                            </a>
                            <div class="text-sm text-secondary-500 mt-1">
                                {{ $job->painter->name }} | {{ $job->created_at->format('Y/m/d') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.jobs.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                        すべて見る →
                    </a>
                </div>
            @else
                <p class="text-secondary-500 text-sm">依頼がありません</p>
            @endif
        </div>
    </div>

    {{-- 最近の応募 --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">最近の応募</h2>
        </div>
        <div class="card-body">
            @if($recentApplications->count() > 0)
                <div class="space-y-3">
                    @foreach($recentApplications as $application)
                        <div class="border-b border-secondary-200 pb-3 last:border-0 last:pb-0">
                            <div class="text-sm text-secondary-900">
                                <span class="badge badge-{{ $application->status === 'accepted' ? 'success' : ($application->status === 'rejected' ? 'error' : 'warning') }}">
                                    {{ $application->status_label }}
                                </span>
                            </div>
                            <div class="text-sm text-secondary-700 mt-1">
                                {{ $application->model->name }} → {{ $application->job->title }}
                            </div>
                            <div class="text-xs text-secondary-500 mt-1">
                                {{ $application->created_at->format('Y/m/d H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-secondary-500 text-sm">応募がありません</p>
            @endif
        </div>
    </div>

    {{-- 最近のお問い合わせ --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">最近のお問い合わせ</h2>
        </div>
        <div class="card-body">
            @if($recentContacts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentContacts as $contact)
                        <div class="border-b border-secondary-200 pb-3 last:border-0 last:pb-0">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                {{ $contact->subject }}
                            </a>
                            <div class="text-sm text-secondary-500 mt-1">
                                {{ $contact->name }} | {{ $contact->created_at->format('Y/m/d') }}
                                @if(!$contact->is_read)
                                    <span class="badge badge-error ml-2">未読</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.contacts.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                        すべて見る →
                    </a>
                </div>
            @else
                <p class="text-secondary-500 text-sm">お問い合わせがありません</p>
            @endif
        </div>
    </div>
</div>
@endsection

