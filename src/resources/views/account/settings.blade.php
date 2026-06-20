@extends('layouts.app')

@section('title', 'アカウント設定')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">アカウント設定</span>
        </div>
        <p class="page-header-subtitle">Account Settings</p>
        <h1 class="page-header-title mt-2">アカウント設定</h1>
        <p class="text-secondary-500 text-sm mt-3">ログイン情報の変更や退会手続きはこちらから。</p>
    </div>
</div>

<div class="page-narrow">
    @include('mypage.partials.account-settings')

    <div class="border-t border-secondary-200 pt-6 mt-8">
        <a href="{{ route('mypage') }}"
           class="inline-flex items-center gap-2 text-sm text-secondary-500 hover:text-secondary-900 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            マイページに戻る
        </a>
    </div>
</div>
@endsection
