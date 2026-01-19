@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800 text-sm">
            ← 依頼詳細に戻る
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">レビューを投稿</h1>

    @if($existingReview)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <p class="text-yellow-800 font-semibold mb-2">既にレビューを投稿済みです</p>
            <div class="mt-4">
                <p class="text-sm text-yellow-700">
                    <strong>評価:</strong> {{ $existingReview->rating_label }}
                </p>
                @if($existingReview->comment)
                    <p class="text-sm text-yellow-700 mt-2">
                        <strong>コメント:</strong> {{ $existingReview->comment }}
                    </p>
                @endif
            </div>
        </div>
    @endif

    <div class="bg-white border rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-2">{{ $job->title }}</h2>
        <p class="text-gray-600 mb-4">
            レビュー対象: <span class="font-semibold">{{ $otherUser->name }}</span>
        </p>
    </div>

    @if(!$existingReview)
        <form action="{{ route('reviews.store', $job) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="reviewed_user_id" value="{{ $otherUser->id }}">

            {{-- 評価 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    評価 <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="rating" 
                               value="very_good" 
                               required
                               class="mr-2">
                        <span>非常に良い・良い</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="rating" 
                               value="good" 
                               required
                               class="mr-2">
                        <span>良い</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="rating" 
                               value="bad" 
                               required
                               class="mr-2">
                        <span>悪い</span>
                    </label>
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- コメント --}}
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                    コメント（任意）
                </label>
                <textarea id="comment" 
                          name="comment" 
                          rows="5"
                          placeholder="レビューコメントを入力してください"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- 送信ボタン --}}
            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    レビューを投稿
                </button>
                <a href="{{ route('jobs.show', $job) }}" 
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    キャンセル
                </a>
            </div>
        </form>
    @endif
</div>
@endsection

