@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('jobs.index') }}" class="link-primary text-sm">
            ← 依頼一覧に戻る
        </a>
    </div>

    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-bold text-secondary-900">{{ $job->title }}</h1>
                @auth
                    @if($isFavorite)
                        <form action="{{ route('favorites.destroy.job', $job) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn-outline-sm flex items-center gap-2 text-error-600 border-error-600 hover:bg-error-50">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                </svg>
                                お気に入り解除
                            </button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store.job', $job) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="btn-secondary-sm flex items-center gap-2">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" fill-rule="evenodd"/>
                                </svg>
                                お気に入りに追加
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            <div class="flex items-center text-secondary-600 mb-6">
                <span>投稿者: {{ $job->painter->name }}</span>
                <span class="mx-2">|</span>
                <span>投稿日: {{ $job->created_at->format('Y年m月d日') }}</span>
            </div>

            {{-- 基本情報 --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-secondary-50 rounded-lg">
                <div>
                    <div class="text-sm text-secondary-600">場所</div>
                    <div class="font-semibold text-secondary-900">
                        {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                        @if($job->prefecture)
                            <br><span class="text-sm">({{ $job->prefecture }}{{ $job->city ? ' ' . $job->city : '' }})</span>
                        @endif
                    </div>
                </div>

                @if($job->reward_amount)
                    <div>
                        <div class="text-sm text-secondary-600">報酬</div>
                        <div class="font-semibold text-primary-600">
                            {{ number_format($job->reward_amount) }}円
                            @if($job->reward_unit === 'per_hour')
                                /時間
                            @else
                                /回
                            @endif
                        </div>
                    </div>
                @endif

                @if($job->scheduled_date)
                    <div>
                        <div class="text-sm text-secondary-600">日程</div>
                        <div class="font-semibold text-secondary-900">{{ $job->scheduled_date->format('Y/m/d') }}</div>
                    </div>
                @endif

                @if($job->apply_deadline)
                    <div>
                        <div class="text-sm text-secondary-600">応募締切</div>
                        <div class="font-semibold text-secondary-900">{{ $job->apply_deadline->format('Y/m/d') }}</div>
                    </div>
                @endif
            </div>

            {{-- 説明 --}}
            <div class="mb-6">
                <h2 class="section-subtitle">依頼内容</h2>
                <div class="prose max-w-none">
                    <p class="whitespace-pre-wrap text-secondary-700">{{ $job->description }}</p>
                </div>
            </div>

            {{-- 用途 --}}
            @if($job->usage_purpose)
                <div class="mb-6">
                    <h2 class="section-subtitle">用途</h2>
                    <p class="text-secondary-700">{{ $job->usage_purpose }}</p>
                </div>
            @endif

            {{-- 応募状況 --}}
            @if($job->applications()->count() > 0)
                <div class="mb-6 p-4 bg-primary-50 rounded-lg border border-primary-200">
                    <p class="text-sm text-primary-800">
                        現在 {{ $job->applications()->count() }}件の応募があります
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- 応募フォーム（モデルユーザーのみ） --}}
    @auth
        @if(auth()->user()->role === 'model')
            @if($hasApplied)
                <div class="card mb-6 border-warning-200 bg-warning-50">
                    <div class="card-body">
                        <p class="text-warning-800 font-semibold">この依頼には既に応募済みです</p>
                    </div>
                </div>
            @else
                <div class="card mb-6">
                    <div class="card-body">
                        <h2 class="section-subtitle mb-4">この依頼に応募する</h2>
                        <form action="{{ route('model.jobs.apply', $job) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    メッセージ（任意）
                                </label>
                                <textarea id="message" 
                                          name="message" 
                                          rows="5"
                                          placeholder="応募メッセージを入力してください"
                                          class="form-input"></textarea>
                            </div>
                            <button type="submit" class="btn-primary">
                                応募する
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @else
            <div class="card mb-6">
                <div class="card-body">
                    <p class="text-secondary-600">
                        モデルアカウントでログインすると応募できます
                    </p>
                </div>
            </div>
        @endif
    @else
        <div class="card mb-6">
            <div class="card-body">
                <p class="text-secondary-600 mb-4">
                    この依頼に応募するにはログインが必要です
                </p>
                <a href="{{ route('login-register') }}" class="btn-primary">
                    ログインする
                </a>
            </div>
        </div>
    @endauth

    {{-- レビューセクション --}}
    @if($job->reviews->count() > 0)
        <div class="card mb-6">
            <div class="card-body">
                <h2 class="section-subtitle mb-4">レビュー</h2>
                <div class="space-y-4">
                    @foreach($job->reviews as $review)
                        <div class="border-b border-secondary-200 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-secondary-900">
                                        {{ $review->reviewer->name }} → {{ $review->reviewedUser->name }}
                                    </p>
                                    <p class="text-sm text-secondary-600">
                                        {{ $review->created_at->format('Y年m月d日') }}
                                    </p>
                                </div>
                                <span class="badge badge-success">
                                    {{ $review->rating_label }}
                                </span>
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-secondary-700 mt-2 whitespace-pre-wrap">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- レビュー投稿リンク --}}
    @auth
        @if($canReview && $reviewTarget)
            @php
                $existingReview = $job->reviews()
                    ->where('reviewer_id', Auth::id())
                    ->where('reviewed_user_id', $reviewTarget->id)
                    ->first();
            @endphp
            @if(!$existingReview)
                <div class="card mb-6">
                    <div class="card-body">
                        <a href="{{ route('reviews.create', $job) }}" class="btn-primary bg-success-600 hover:bg-success-700">
                            レビューを投稿する
                        </a>
                    </div>
                </div>
            @endif
        @endif
    @endauth

    {{-- 画家の情報（任意） --}}
    @if($job->painter->painterProfile)
        <div class="card">
            <div class="card-body">
                <h2 class="section-subtitle mb-4">投稿者情報</h2>
                <p class="text-secondary-700">{{ $job->painter->name }}</p>
                @if($job->painter->painterProfile->bio)
                    <p class="mt-2 text-secondary-700">{{ $job->painter->painterProfile->bio }}</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

