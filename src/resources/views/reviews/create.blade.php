@extends('layouts.app')

@section('title', 'レビューを投稿')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('jobs.show', $job) }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                依頼詳細
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">レビュー</span>
        </div>
        <p class="page-header-subtitle">Post a Review</p>
        <h1 class="page-header-title mt-2">レビューを投稿</h1>
        <p class="text-secondary-500 text-sm mt-3">完了した取引について、率直なフィードバックをお寄せください。</p>
    </div>
</div>

<div class="page-narrow space-y-5">

    {{-- 対象案件 --}}
    <div class="border border-secondary-200 bg-canvas-50 px-5 sm:px-6 py-5">
        <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-1">Target</p>
        <h2 class="font-display text-lg font-semibold text-secondary-900 mb-1">{{ $job->title }}</h2>
        <p class="text-sm text-secondary-600">
            レビュー対象: <span class="text-secondary-900 font-medium">{{ $otherUser->name ?? '退会済みユーザー' }}</span>
        </p>
    </div>

    @if($existingReview)
        {{-- 投稿済み --}}
        <div class="border-l-2 border-warning-500 bg-canvas-50 px-5 py-5">
            <p class="text-[10px] uppercase tracking-[0.3em] text-warning-700 mb-2">Already Posted</p>
            <p class="text-sm text-secondary-700 mb-4">既にレビューを投稿済みです。一度の取引につき1回のみ投稿可能です。</p>

            <dl class="space-y-2 text-sm border-t border-secondary-200 pt-4">
                <div class="flex gap-4">
                    <dt class="text-[10px] uppercase tracking-[0.25em] text-secondary-500 w-20 shrink-0 pt-0.5">Rating</dt>
                    <dd class="text-secondary-900 font-medium">{{ $existingReview->rating_label }}</dd>
                </div>
                @if($existingReview->comment)
                    <div class="flex gap-4">
                        <dt class="text-[10px] uppercase tracking-[0.25em] text-secondary-500 w-20 shrink-0 pt-0.5">Comment</dt>
                        <dd class="text-secondary-700 whitespace-pre-wrap leading-relaxed">{{ $existingReview->comment }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    @else
        {{-- 投稿フォーム --}}
        <form action="{{ route('reviews.store', $job) }}" method="POST" class="border border-secondary-200 bg-canvas-50 p-5 sm:p-8 space-y-6">
            @csrf
            <input type="hidden" name="reviewed_user_id" value="{{ $otherUser->id }}">

            {{-- 評価 --}}
            <div>
                <p class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-3">Rating <span class="text-error-500">*</span></p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    @php
                        $ratings = [
                            ['value' => 'very_good', 'label' => '非常に良い', 'sub' => 'Very Good',
                                'checked' => 'peer-checked:border-success-500 peer-checked:bg-success-500 peer-checked:text-canvas-50',
                                'subChecked' => 'peer-checked:text-canvas-50/80'],
                            ['value' => 'good',      'label' => '良い',       'sub' => 'Good',
                                'checked' => 'peer-checked:border-warning-500 peer-checked:bg-warning-500 peer-checked:text-canvas-50',
                                'subChecked' => 'peer-checked:text-canvas-50/80'],
                            ['value' => 'bad',       'label' => '悪い',       'sub' => 'Bad',
                                'checked' => 'peer-checked:border-error-500 peer-checked:bg-error-500 peer-checked:text-canvas-50',
                                'subChecked' => 'peer-checked:text-canvas-50/80'],
                        ];
                    @endphp
                    @foreach($ratings as $r)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $r['value'] }}"
                                   {{ old('rating') === $r['value'] ? 'checked' : '' }}
                                   required class="peer sr-only">
                            <span class="block text-center py-3 border border-secondary-300 text-sm text-secondary-700 {{ $r['checked'] }} transition-colors duration-200">
                                {{ $r['label'] }}
                                <span class="block text-[9px] tracking-[0.2em] uppercase text-secondary-500 {{ $r['subChecked'] }}">{{ $r['sub'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('rating')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- コメント --}}
            <div>
                <label for="comment" class="form-label">コメント（任意）</label>
                <textarea id="comment" name="comment" rows="6"
                          placeholder="撮影現場の雰囲気・コミュニケーション・進行など、率直な感想をお寄せください。"
                          class="form-textarea">{{ old('comment') }}</textarea>
                <p class="form-help">2000文字以内</p>
                @error('comment')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- 送信 --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-4 border-t border-secondary-200">
                <a href="{{ route('jobs.show', $job) }}"
                   class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-sm hover:bg-secondary-100 transition-colors duration-200 text-center">
                    キャンセル
                </a>
                <button type="submit"
                        class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                    レビューを投稿
                </button>
            </div>
        </form>
    @endif

</div>
@endsection
