@extends('layouts.app')

@section('title', 'よくある質問 (Q&A)')

@section('content')

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">よくある質問 (Q&amp;A)</span>
        </div>
        <p class="page-header-subtitle">Q &amp; A</p>
        <h1 class="page-header-title mt-2">よくある質問 (Q&amp;A)</h1>
        <p class="text-secondary-500 text-sm mt-3">画家からよく聞かれそうな質問を、ご自身で「質問」と「回答」のセットで作成できます。</p>
    </div>
</div>

<div class="page-narrow space-y-8">

    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-success-700 mb-1 font-medium">完了</p>
            {{ session('success') }}
        </div>
    @endif

    @unless($modelProfile)
        <div class="border border-dashed border-secondary-300 px-5 py-12 text-center">
            <p class="text-sm text-secondary-500">先にプロフィールを作成してください。</p>
            <a href="{{ route('model.profile.edit') }}" class="btn-museum-dark inline-flex mt-4">プロフィール編集へ</a>
        </div>
    @else
        {{-- 新規追加フォーム --}}
        <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
            <h2 class="font-display text-lg font-semibold text-secondary-900 mb-4">新しい Q&amp;A を追加</h2>
            <form action="{{ route('model.questions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="question" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">質問 <span class="text-error-500">*</span></label>
                    <textarea id="question" name="question" rows="2" required maxlength="1000"
                              placeholder="例：撮影中の服装は指定がありますか？"
                              class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900">{{ old('question') }}</textarea>
                    @error('question')<p class="text-xs text-error-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="answer" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">回答 <span class="text-error-500">*</span></label>
                    <textarea id="answer" name="answer" rows="4" required maxlength="2000"
                              placeholder="例：基本的にはご相談しながら決めますが、私服での撮影もご対応可能です。"
                              class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900">{{ old('answer') }}</textarea>
                    @error('answer')<p class="text-xs text-error-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-museum-dark">追加する</button>
                </div>
            </form>
        </section>

        {{-- 一覧 --}}
        <section>
            <h2 class="font-display text-lg font-semibold text-secondary-900 mb-4">登録済みの Q&amp;A</h2>
            @if($questions->isEmpty())
                <div class="border border-dashed border-secondary-300 px-5 py-12 text-center">
                    <p class="text-sm text-secondary-500">まだ Q&amp;A が登録されていません。</p>
                </div>
            @else
                <ul class="space-y-4">
                    @foreach($questions as $qa)
                        <li class="border border-secondary-200 bg-canvas-50 px-5 py-4">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mt-1 shrink-0">Q.</span>
                                    <p class="text-secondary-900 leading-relaxed whitespace-pre-wrap">{{ $qa->question }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('model.questions.edit', $qa) }}" class="text-xs text-secondary-600 hover:text-secondary-900 underline">編集</a>
                                    <form action="{{ route('model.questions.destroy', $qa) }}" method="POST"
                                          onsubmit="return confirm('この Q&A を削除してよろしいですか？');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-error-600 hover:text-error-700 underline">削除</button>
                                    </form>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pt-3 border-t border-secondary-200">
                                <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mt-1 shrink-0">A.</span>
                                <p class="text-secondary-700 whitespace-pre-wrap leading-relaxed">{{ $qa->answer }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 flex justify-center">
                    {{ $questions->links() }}
                </div>
            @endif
        </section>
    @endunless
</div>

@endsection
