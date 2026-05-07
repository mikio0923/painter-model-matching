@extends('layouts.app')

@section('title', 'あなたへの質問')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <p class="page-header-subtitle">Q & A</p>
        <h1 class="page-header-title mt-2">あなたへの質問</h1>
        <p class="text-secondary-500 text-sm mt-3">画家からプロフィールについて寄せられた質問と、ご自身の回答を管理できます。</p>
    </div>
</div>

<div class="page-narrow">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Updated</p>
            {{ session('success') }}
        </div>
    @endif

    @if(!$modelProfile)
        <div class="border-l-2 border-warning-500 bg-canvas-50 px-5 py-5">
            <p class="text-[10px] uppercase tracking-[0.3em] text-warning-700 mb-2">Notice</p>
            <p class="text-sm text-secondary-700 mb-4">プロフィールを作成すると、画家から質問を受け付けられるようになります。</p>
            <a href="{{ route('model.profile.edit') }}" class="btn-museum-dark inline-flex">
                プロフィールを作成する
            </a>
        </div>
    @elseif($questions->isEmpty())
        <div class="border border-secondary-200 px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Questions</p>
            <p class="text-secondary-500 text-sm">まだ質問は届いていません。</p>
            <p class="text-xs text-secondary-400 mt-2">プロフィールを充実させると、画家から質問が届きやすくなります。</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach($questions as $question)
                <article class="border border-secondary-200 bg-canvas-50">
                    <div class="px-5 sm:px-6 py-5">
                        <div class="flex items-baseline gap-3 mb-3">
                            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">
                                {{ $question->asker->name ?? '退会済みユーザー' }}
                            </p>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                {{ $question->created_at->format('Y . n . j  H:i') }}
                            </p>
                        </div>
                        <p class="text-secondary-900 whitespace-pre-wrap leading-relaxed">{{ $question->question }}</p>
                    </div>

                    @if($question->answer)
                        <div class="border-t border-secondary-200 px-5 sm:px-6 py-5 bg-secondary-50">
                            <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Your Answer</p>
                            <p class="text-secondary-800 text-sm whitespace-pre-wrap leading-relaxed">{{ $question->answer }}</p>
                            <div class="mt-3">
                                <a href="{{ route('model.questions.edit', $question) }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                                    Edit Answer →
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="border-t border-secondary-200 px-5 sm:px-6 py-5 bg-secondary-50">
                            <form action="{{ route('model.questions.answer', $question) }}" method="POST" class="space-y-3">
                                @csrf
                                <label for="answer-{{ $question->id }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 block">Reply</label>
                                <textarea id="answer-{{ $question->id }}" name="answer" rows="4" required
                                          placeholder="回答を入力してください"
                                          class="form-textarea">{{ old('answer') }}</textarea>
                                @error('answer')<p class="form-error">{{ $message }}</p>@enderror
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 bg-secondary-900 text-canvas-50 border border-secondary-900 text-[10px] uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                                        Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $questions->links() }}
        </div>
    @endif
</div>
@endsection
