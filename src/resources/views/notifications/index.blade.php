@extends('layouts.app')

@section('title', '通知')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <p class="page-header-subtitle">Notifications</p>
        <h1 class="page-header-title mt-2">通知</h1>
        <p class="text-secondary-500 text-sm mt-3">応募・メッセージ・レビューなど、サービス内のアクティビティをまとめてご確認いただけます。</p>
    </div>
</div>

<div class="page-narrow">

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-xs text-success-700 mb-1 font-medium">更新しました</p>
            {{ session('success') }}
        </div>
    @endif

    {{-- 一括既読ボタン --}}
    @if($notifications->count() > 0)
        <div class="flex justify-end mb-6">
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-5 py-2 border border-secondary-400 text-secondary-700 text-sm hover:bg-secondary-100 transition-colors duration-200">
                    すべて既読にする
                </button>
            </form>
        </div>
    @endif

    @if($notifications->count() === 0)
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-sm text-secondary-500">現在、新しい通知はありません。</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                @php
                    $isUnread = $notification->isUnread();
                    $isAcceptance = $notification->type === 'application_accepted';
                    // 採用通知は赤枠＋赤強調で目立たせる
                    $borderClass = $isAcceptance ? 'border-error-500' : 'border-secondary-900';
                    $titleClass  = $isAcceptance ? 'text-error-600 font-bold' : 'font-medium text-secondary-900';
                    $bodyClass   = $isAcceptance ? 'text-error-700 font-medium' : 'text-secondary-600';
                @endphp
                <div class="flex items-start gap-4 p-5 border-2 {{ $borderClass }} rounded-md {{ $isUnread ? 'bg-canvas-100' : 'bg-canvas-50' }}">

                    {{-- マーカー（未読は赤丸、既読は薄い丸） --}}
                    <div class="shrink-0 mt-2">
                        <span class="block w-2 h-2 rounded-full {{ $isUnread ? 'bg-error-500' : 'bg-secondary-200' }}"></span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="leading-snug {{ $titleClass }}">
                                {{ $notification->title }}
                            </h3>
                            @if($isUnread)
                                <span class="text-xs border border-error-500 bg-error-50 text-error-700 px-2 py-0.5 shrink-0 font-medium">未読</span>
                            @endif
                        </div>
                        @if($notification->body)
                            <p class="text-sm leading-relaxed mb-2 whitespace-pre-line {{ $bodyClass }}">{{ $notification->body }}</p>
                        @endif
                        <p class="text-xs text-secondary-400">
                            {{ $notification->created_at->format('Y年n月j日 H:i') }}
                            @if($notification->read_at)
                                ・既読 {{ $notification->read_at->format('n月j日 H:i') }}
                            @endif
                        </p>
                    </div>

                    {{-- アクション（クリック時に自動既読化＋関連画面へ） --}}
                    <div class="shrink-0">
                        <a href="{{ route('notifications.read', $notification) }}"
                           class="text-sm {{ $isUnread ? 'text-secondary-700 font-medium' : 'text-secondary-400' }} hover:text-secondary-900 transition-colors">
                            詳細を見る →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
