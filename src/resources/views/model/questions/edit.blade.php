@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('model.questions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">← 質問一覧に戻る</a>
    </div>

    <h1 class="text-2xl font-bold mb-6">回答を編集</h1>

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
        <div class="text-sm text-gray-500 mb-2">{{ $question->asker->name }} さんからの質問（{{ $question->created_at->format('Y年m月d日') }}）</div>
        <p class="text-gray-900 whitespace-pre-wrap">{{ $question->question }}</p>
    </div>

    <form action="{{ route('model.questions.answer', $question) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="answer" class="block text-sm font-medium text-gray-700 mb-1">回答</label>
            <textarea id="answer" name="answer" rows="6" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('answer') border-red-500 @enderror"
                placeholder="回答を入力してください">{{ old('answer', $question->answer) }}</textarea>
            @error('answer')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                保存する
            </button>
            <a href="{{ route('model.questions.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection
