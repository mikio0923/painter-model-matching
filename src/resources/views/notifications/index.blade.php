@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">通知一覧</h1>
        <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                すべて既読にする
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($notifications->count() === 0)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600">通知はありません</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="bg-white border rounded-lg p-4 hover:shadow-md transition-shadow {{ $notification->isUnread() ? 'border-blue-300 bg-blue-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-semibold text-lg">{{ $notification->title }}</h3>
                                @if($notification->isUnread())
                                    <span class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-full">新着</span>
                                @endif
                            </div>
                            @if($notification->body)
                                <p class="text-gray-600 text-sm mb-2">{{ $notification->body }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>{{ $notification->created_at->format('Y年m月d日 H:i') }}</span>
                                @if($notification->read_at)
                                    <span class="text-gray-400">既読: {{ $notification->read_at->format('Y年m月d日 H:i') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            @if($notification->isUnread())
                                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                        既読にする
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('notifications.read', $notification) }}" 
                                   class="bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-300">
                                    詳細を見る
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

