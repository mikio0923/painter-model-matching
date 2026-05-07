@extends('layouts.app')

@section('title', '通知')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <p class="page-header-subtitle">Notifications</p>
        <h1 class="page-header-title mt-2">通知</h1>
        <p class="text-secondary-500 text-sm mt-3">応募・メッセージ・レビューなど、サービス内のアクティビティをまとめてご確認いただけます。</p>
    </div>
</div>

<div class="page-narrow">

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Updated</p>
            {{ session('success') }}
        </div>
    @endif

    {{-- 一括既読ボタン --}}
    @if($notifications->count() > 0)
        <div class="flex justify-end mb-6">
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-5 py-2 border border-secondary-400 text-secondary-700 text-[10px] uppercase tracking-[0.25em] hover:bg-secondary-100 transition-colors duration-200">
                    Mark All as Read
                </button>
            </form>
        </div>
    @endif

    @if($notifications->count() === 0)
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Notifications</p>
            <p class="text-secondary-500 text-sm">現在、新しい通知はありません。</p>
        </div>
    @else
        <div class="border-t border-secondary-200">
            @foreach($notifications as $notification)
                @php $isUnread = $notification->isUnread(); @endphp
                <div class="flex items-start gap-4 py-5 px-1 border-b border-secondary-200 {{ $isUnread ? 'bg-canvas-100' : '' }}">

                    {{-- マーカー（未読は黒丸、既読は薄い丸） --}}
                    <div class="shrink-0 mt-2">
                        <span class="block w-2 h-2 rounded-full {{ $isUnread ? 'bg-secondary-900' : 'bg-secondary-200' }}"></span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="font-medium text-secondary-900 leading-snug">
                                {{ $notification->title }}
                            </h3>
                            @if($isUnread)
                                <span class="text-[9px] tracking-[0.25em] uppercase border border-secondary-900 text-secondary-900 px-1.5 py-0.5 shrink-0">New</span>
                            @endif
                        </div>
                        @if($notification->body)
                            <p class="text-sm text-secondary-600 leading-relaxed mb-2">{{ $notification->body }}</p>
                        @endif
                        <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                            {{ $notification->created_at->format('Y . n . j  H:i') }}
                            @if($notification->read_at)
                                · Read {{ $notification->read_at->format('n.j  H:i') }}
                            @endif
                        </p>
                    </div>

                    {{-- アクション --}}
                    <div class="shrink-0">
                        @if($isUnread)
                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                                    Mark Read
                                </button>
                            </form>
                        @else
                            <a href="{{ route('notifications.read', $notification) }}"
                               class="text-[10px] tracking-[0.25em] uppercase text-secondary-400 hover:text-secondary-900 transition-colors">
                                Detail →
                            </a>
                        @endif
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
