@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('messages.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Messages
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current max-w-xs">{{ $otherUser->name ?? '退会済みユーザー' }}</span>
        </div>
        <p class="page-header-subtitle">Conversation</p>
        <h1 class="page-header-title mt-2">{{ $job->title }}</h1>
        <p class="page-header-meta mt-2">
            相手: <span class="text-secondary-700">{{ $otherUser->name ?? '退会済みユーザー' }}</span>
        </p>
    </div>
</div>

<div class="page-narrow space-y-6">

    {{-- メッセージ一覧 --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 max-h-[600px] overflow-y-auto">
        @if($messages->isEmpty())
            <div class="text-center py-12">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-2">No Messages</p>
                <p class="text-secondary-500 text-sm">最初のメッセージを送ってください。</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($messages as $message)
                    @php $isMe = $message->sender_id === Auth::id(); @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] sm:max-w-md">
                            <div class="flex items-center gap-2 mb-1.5 {{ $isMe ? 'justify-end' : '' }}">
                                <span class="text-xs font-medium text-secondary-700">
                                    {{ $message->sender->name ?? '退会済みユーザー' }}
                                </span>
                                <span class="text-[10px] tracking-[0.15em] uppercase text-secondary-400">
                                    {{ $message->created_at->format('m/d H:i') }}
                                </span>
                            </div>
                            <div class="px-4 py-3 text-sm whitespace-pre-wrap leading-relaxed {{ $isMe ? 'bg-secondary-900 text-canvas-50 border border-secondary-900' : 'bg-canvas-50 text-secondary-800 border border-secondary-300' }}">{{ $message->body }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- メッセージ送信フォーム --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        <form action="{{ route('messages.store', $job) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $otherUser->id ?? '' }}">

            <div>
                <label for="body" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">
                    Send Message
                </label>
                <textarea id="body" name="body" rows="4" required
                          placeholder="メッセージを入力してください"
                          class="form-textarea"></textarea>
                @error('body')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                    Send
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
