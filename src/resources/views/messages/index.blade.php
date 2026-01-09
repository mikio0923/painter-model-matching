@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold mb-6">メッセージ一覧</h1>

    @if($threads->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4">メッセージがありません</p>
            <p class="text-sm text-gray-500">
                依頼に応募して承認されると、メッセージのやり取りができるようになります
            </p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($threads as $thread)
                <a href="{{ route('messages.show', ['job' => $thread->job, 'with' => $thread->other_user->id]) }}" 
                   class="block border rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-2">
                                <h2 class="text-lg font-semibold">{{ $thread->job->title }}</h2>
                                @if($thread->unread_count > 0)
                                    <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                        {{ $thread->unread_count }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-2">
                                <span class="font-semibold">相手:</span> {{ $thread->other_user->name }}
                            </div>

                            @if($thread->last_message)
                                <div class="text-sm text-gray-700 mt-2">
                                    <p class="line-clamp-2">
                                        {{ mb_strlen($thread->last_message->body) > 100 
                                            ? mb_substr($thread->last_message->body, 0, 100) . '...' 
                                            : $thread->last_message->body }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $thread->last_message->created_at->format('Y/m/d H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

