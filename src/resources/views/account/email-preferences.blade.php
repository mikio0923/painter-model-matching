@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">メール配信設定</span>
        </div>
        <p class="page-header-subtitle">Email Preferences</p>
        <h1 class="page-header-title mt-2">メール配信設定</h1>
        <p class="text-secondary-500 text-sm mt-3">受信したいメールの種類を選択できます。アプリ内通知は影響を受けません。</p>
    </div>
</div>

<div class="page-narrow">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Saved</p>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('account.email-preferences.update') }}" class="border border-secondary-200 bg-canvas-50 divide-y divide-secondary-200">
        @csrf
        @method('PUT')

        @php
            $items = [
                ['name' => 'application_emails', 'label' => '応募関連メール',     'sub' => 'Applications', 'desc' => '応募の受信・承認・却下のお知らせ', 'value' => $pref->application_emails],
                ['name' => 'message_emails',     'label' => 'メッセージ受信',     'sub' => 'Messages',     'desc' => '画家・モデルからのメッセージ通知', 'value' => $pref->message_emails],
                ['name' => 'review_emails',      'label' => 'レビュー通知',       'sub' => 'Reviews',      'desc' => 'レビューが投稿されたときのお知らせ', 'value' => $pref->review_emails],
                ['name' => 'reminder_emails',   'label' => 'リマインダー',       'sub' => 'Reminders',   'desc' => '応募締切の事前通知など', 'value' => $pref->reminder_emails],
                ['name' => 'marketing_emails',  'label' => 'お知らせ・案内',     'sub' => 'Newsletter',  'desc' => '新機能のご紹介やキャンペーン情報', 'value' => $pref->marketing_emails],
            ];
        @endphp

        @foreach($items as $item)
            <label class="flex items-start gap-4 px-5 py-5 cursor-pointer hover:bg-secondary-50 transition-colors duration-200">
                <input type="checkbox" name="{{ $item['name'] }}" value="1"
                       {{ $item['value'] ? 'checked' : '' }}
                       class="mt-1 w-4 h-4 border-secondary-400 text-secondary-900 focus:ring-secondary-700 shrink-0">
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">{{ $item['sub'] }}</p>
                    <p class="font-medium text-secondary-900">{{ $item['label'] }}</p>
                    <p class="text-xs text-secondary-500 mt-1 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            </label>
        @endforeach

        <div class="px-5 py-5 flex justify-end">
            <button type="submit"
                    class="px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
