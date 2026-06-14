@extends('admin.layouts.app')

@section('content')

{{-- ヘッダー --}}
<div class="border-b border-secondary-200 pb-5 mb-8 flex items-end justify-between gap-4 flex-wrap">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                ユーザー一覧
            </a>
            <span class="text-secondary-300">/</span>
            <span class="text-sm text-secondary-700">詳細</span>
        </div>
        <h1 class="font-display text-2xl font-semibold text-secondary-900">{{ $user->name }}</h1>
        <p class="text-sm text-secondary-500 mt-1">ユーザーの詳細情報</p>
    </div>
</div>

@php
    $roleInfo = match($user->role) {
        'admin'   => ['label' => '管理者', 'border' => 'border-error-500',   'bg' => 'bg-error-50',   'text' => 'text-error-700'],
        'painter' => ['label' => '画家',   'border' => 'border-primary-500', 'bg' => 'bg-primary-50', 'text' => 'text-primary-700'],
        default   => ['label' => 'モデル', 'border' => 'border-success-500', 'bg' => 'bg-success-50', 'text' => 'text-success-700'],
    };
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- 基本情報 --}}
    <div class="border border-secondary-200 bg-canvas-50">
        <div class="px-5 py-3 border-b border-secondary-200">
            <h2 class="font-display text-base font-semibold text-secondary-900">基本情報</h2>
        </div>
        <dl class="divide-y divide-secondary-200">
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">ID</dt>
                <dd class="text-sm text-secondary-900">{{ $user->id }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">名前</dt>
                <dd class="text-sm text-secondary-900">{{ $user->name }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">メールアドレス</dt>
                <dd class="text-sm text-secondary-900 break-all">{{ $user->email }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4 items-center">
                <dt class="text-sm text-secondary-500 w-28 shrink-0">ロール</dt>
                <dd>
                    <span class="inline-flex items-center px-2.5 py-0.5 border {{ $roleInfo['border'] }} {{ $roleInfo['bg'] }} text-xs font-medium {{ $roleInfo['text'] }}">
                        {{ $roleInfo['label'] }}
                    </span>
                </dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">登録日</dt>
                <dd class="text-sm text-secondary-900">{{ $user->created_at->format('Y年n月j日 H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- プロフィール情報 --}}
    <div class="border border-secondary-200 bg-canvas-50">
        <div class="px-5 py-3 border-b border-secondary-200">
            <h2 class="font-display text-base font-semibold text-secondary-900">プロフィール情報</h2>
        </div>
        @if($user->role === 'model' && $user->modelProfile)
            <dl class="divide-y divide-secondary-200">
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">表示名</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->modelProfile->display_name }}</dd>
                </div>
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">都道府県</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->modelProfile->prefecture ?? '未設定' }}</dd>
                </div>
                <div class="px-5 py-3 flex gap-4 items-center">
                    <dt class="text-sm text-secondary-500 w-28 shrink-0">公開状態</dt>
                    <dd>
                        @if($user->modelProfile->is_public)
                            <span class="inline-flex items-center px-2.5 py-0.5 border border-success-500 bg-success-50 text-xs font-medium text-success-700">公開中</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 border border-secondary-300 bg-secondary-50 text-xs font-medium text-secondary-600">非公開</span>
                        @endif
                    </dd>
                </div>
            </dl>
        @elseif($user->role === 'painter' && $user->painterProfile)
            <dl class="divide-y divide-secondary-200">
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">表示名</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->painterProfile->display_name }}</dd>
                </div>
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">都道府県</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->painterProfile->prefecture ?? '未設定' }}</dd>
                </div>
            </dl>
        @else
            <div class="px-5 py-12 text-center">
                <p class="text-secondary-500 text-sm">プロフィール情報がありません。</p>
            </div>
        @endif
    </div>
</div>

{{-- アクティビティ --}}
<div class="border border-secondary-200 bg-canvas-50 mb-6">
    <div class="px-5 py-3 border-b border-secondary-200">
        <h2 class="font-display text-base font-semibold text-secondary-900">アクティビティ</h2>
    </div>
    <div class="px-5 py-5 grid grid-cols-2 gap-6">
        <div>
            <p class="text-sm text-secondary-500">投稿した依頼</p>
            <p class="font-display text-2xl text-secondary-900 mt-1">{{ $user->jobs->count() }} 件</p>
        </div>
        <div>
            <p class="text-sm text-secondary-500">応募した依頼</p>
            <p class="font-display text-2xl text-secondary-900 mt-1">{{ $user->jobApplications->count() }} 件</p>
        </div>
    </div>
</div>

@if($user->role !== 'admin')
    <div class="flex justify-end">
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
              onsubmit="return confirm('本当にこのユーザーを削除しますか？この操作は取り消せません。');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-6 py-2.5 border border-error-500 text-error-600 text-sm hover:bg-error-50 transition-colors duration-200">
                ユーザーを削除
            </button>
        </form>
    </div>
@endif
@endsection
