@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('messages.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
            ← メッセージ一覧に戻る
        </a>
    </div>

    <div class="bg-white border rounded-lg p-6 mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $job->title }}</h1>
        <p class="text-gray-600">
            相手: <span class="font-semibold">{{ $otherUser->name }}</span>
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- メッセージ一覧 --}}
    <div class="bg-white border rounded-lg p-6 mb-6" style="max-height: 500px; overflow-y: auto;">
        @if($messages->isEmpty())
            <p class="text-gray-600 text-center py-8">まだメッセージがありません</p>
        @else
            <div class="space-y-4">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold">
                                    {{ $message->sender->name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $message->created_at->format('m/d H:i') }}
                                </span>
                            </div>
                            <div class="rounded-lg p-3 {{ $message->sender_id === Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                                <p class="whitespace-pre-wrap text-sm">{{ $message->body }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- メッセージ送信フォーム --}}
    <div class="bg-white border rounded-lg p-6">
        <form action="{{ route('messages.store', $job) }}" method="POST">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
            
            <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                    メッセージ
                </label>
                <textarea id="body" 
                          name="body" 
                          rows="4"
                          required
                          placeholder="メッセージを入力してください"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                送信
            </button>
        </form>
    </div>
</div>
@endsection

