@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold mb-2">
        <span class="text-gray-400">&gt;</span> あなたへの質問
    </h1>
    <p class="text-sm text-gray-600 mb-6">画家の方からプロフィールについて寄せられた質問と、あなたの回答が表示されます。</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(!$modelProfile)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
            <p class="text-amber-800 mb-4">プロフィールを作成すると、画家から質問を受け付けられるようになります。</p>
            <a href="{{ route('model.profile.edit') }}" class="inline-block bg-amber-600 text-white px-6 py-2 rounded hover:bg-amber-700">
                プロフィールを作成する
            </a>
        </div>
    @elseif($questions->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-12 text-center">
            <p class="text-gray-600 mb-2">まだ質問は届いていません</p>
            <p class="text-sm text-gray-500">プロフィールを充実させると、画家から質問が届きやすくなります。</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($questions as $question)
                <div class="border border-gray-200 rounded-lg p-6 bg-white hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="text-sm text-gray-500 mb-1">
                                {{ $question->asker->name }} さんからの質問
                                <span class="ml-2">（{{ $question->created_at->format('Y年m月d日 H:i') }}）</span>
                            </div>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $question->question }}</p>
                        </div>
                    </div>

                    @if($question->answer)
                        <div class="mt-4 pl-4 border-l-4 border-blue-200 bg-blue-50 rounded-r p-3">
                            <div class="text-sm font-medium text-blue-800 mb-1">あなたの回答</div>
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $question->answer }}</p>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('model.questions.edit', $question) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                回答を編集する
                            </a>
                        </div>
                    @else
                        <form action="{{ route('model.questions.answer', $question) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-3">
                                <label for="answer-{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-1">回答を入力</label>
                                <textarea id="answer-{{ $question->id }}" name="answer" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('answer') border-red-500 @enderror"
                                    placeholder="質問への回答を入力してください">{{ old('answer') }}</textarea>
                                @error('answer')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                回答を送信
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    @endif
</div>
@endsection
