@extends('admin.layouts.app')

@section('content')

{{-- ヘッダー --}}
<div class="border-b border-secondary-200 pb-5 mb-8 flex items-end justify-between gap-4 flex-wrap">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.users.index') }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Users
            </a>
            <span class="text-secondary-300">/</span>
            <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-700">Detail</span>
        </div>
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">User Detail</p>
        <h1 class="font-display text-2xl font-semibold text-secondary-900">{{ $user->name }}</h1>
    </div>
</div>

@php
    $roleInfo = match($user->role) {
        'admin'   => ['label' => '管理者', 'class' => 'text-error-600'],
        'painter' => ['label' => '画家',   'class' => 'text-secondary-900'],
        default   => ['label' => 'モデル', 'class' => 'text-secondary-700'],
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
                <dd class="text-sm text-secondary-900">{{ $user->id }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Name</dt>
                <dd class="text-sm text-secondary-900">{{ $user->name }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Email</dt>
                <dd class="text-sm text-secondary-900 break-all">{{ $user->email }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Role</dt>
                <dd class="text-[10px] tracking-[0.25em] uppercase {{ $roleInfo['class'] }}">● {{ $roleInfo['label'] }}</dd>
            </div>
            <div class="px-5 py-3 flex gap-4">
                <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Created</dt>
                <dd class="text-sm text-secondary-900">{{ $user->created_at->format('Y年n月j日 H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- プロフィール情報 --}}
    <div class="border border-secondary-200 bg-canvas-50">
        <div class="px-5 py-3 border-b border-secondary-200">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Profile</p>
            <h2 class="font-display text-base font-semibold text-secondary-900">プロフィール情報</h2>
        </div>
        @if($user->role === 'model' && $user->modelProfile)
            <dl class="divide-y divide-secondary-200">
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Display</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->modelProfile->display_name }}</dd>
                </div>
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Prefecture</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->modelProfile->prefecture ?? '未設定' }}</dd>
                </div>
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Visibility</dt>
                    <dd class="text-[10px] tracking-[0.25em] uppercase {{ $user->modelProfile->is_public ? 'text-success-700' : 'text-secondary-500' }}">
                        ● {{ $user->modelProfile->is_public ? '公開' : '非公開' }}
                    </dd>
                </div>
            </dl>
        @elseif($user->role === 'painter' && $user->painterProfile)
            <dl class="divide-y divide-secondary-200">
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Display</dt>
                    <dd class="text-sm text-secondary-900">{{ $user->painterProfile->display_name }}</dd>
                </div>
                <div class="px-5 py-3 flex gap-4">
                    <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-24 shrink-0 pt-0.5">Prefecture</dt>
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
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Activity</p>
        <h2 class="font-display text-base font-semibold text-secondary-900">アクティビティ</h2>
    </div>
    <div class="px-5 py-5 grid grid-cols-2 gap-6">
        <div>
            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">Posted Jobs</p>
            <p class="font-display text-2xl text-secondary-900 mt-1">{{ $user->jobs->count() }}</p>
        </div>
        <div>
            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">Applications</p>
            <p class="font-display text-2xl text-secondary-900 mt-1">{{ $user->jobApplications->count() }}</p>
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
                    class="px-6 py-2.5 border border-error-500 text-error-600 text-xs uppercase tracking-[0.25em] hover:bg-error-50 transition-colors duration-200">
                Delete User
            </button>
        </form>
    </div>
@endif
@endsection
