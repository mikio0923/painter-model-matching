@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
            ← 依頼一覧に戻る
        </a>
    </div>

    <div class="bg-white border rounded-lg p-8 mb-6">
        <h1 class="text-3xl font-bold mb-4">{{ $job->title }}</h1>

        <div class="flex items-center text-gray-600 mb-6">
            <span>投稿者: {{ $job->painter->name }}</span>
            <span class="mx-2">|</span>
            <span>投稿日: {{ $job->created_at->format('Y年m月d日') }}</span>
        </div>

        {{-- 基本情報 --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <div class="text-sm text-gray-600">場所</div>
                <div class="font-semibold">
                    {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                    @if($job->prefecture)
                        <br><span class="text-sm">({{ $job->prefecture }}{{ $job->city ? ' ' . $job->city : '' }})</span>
                    @endif
                </div>
            </div>

            @if($job->reward_amount)
                <div>
                    <div class="text-sm text-gray-600">報酬</div>
                    <div class="font-semibold text-blue-600">
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
                    <div class="text-sm text-gray-600">日程</div>
                    <div class="font-semibold">{{ $job->scheduled_date->format('Y/m/d') }}</div>
                </div>
            @endif

            @if($job->apply_deadline)
                <div>
                    <div class="text-sm text-gray-600">応募締切</div>
                    <div class="font-semibold">{{ $job->apply_deadline->format('Y/m/d') }}</div>
                </div>
            @endif
        </div>

        {{-- 説明 --}}
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-3">依頼内容</h2>
            <div class="prose max-w-none">
                <p class="whitespace-pre-wrap">{{ $job->description }}</p>
            </div>
        </div>

        {{-- 用途 --}}
        @if($job->usage_purpose)
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-3">用途</h2>
                <p>{{ $job->usage_purpose }}</p>
            </div>
        @endif

        {{-- 応募状況 --}}
        @if($job->applications()->count() > 0)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    現在 {{ $job->applications()->count() }}件の応募があります
                </p>
            </div>
        @endif
    </div>

    {{-- 応募フォーム（モデルユーザーのみ） --}}
    @auth
        @if(auth()->user()->role === 'model')
            @if($hasApplied)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <p class="text-yellow-800 font-semibold">この依頼には既に応募済みです</p>
                </div>
            @else
                <div class="bg-white border rounded-lg p-8 mb-6">
                    <h2 class="text-2xl font-bold mb-4">この依頼に応募する</h2>
                    <form action="{{ route('model.jobs.apply', $job) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                メッセージ（任意）
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5"
                                      placeholder="応募メッセージを入力してください"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            応募する
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="bg-gray-50 border rounded-lg p-6 mb-6">
                <p class="text-gray-600">
                    モデルアカウントでログインすると応募できます
                </p>
            </div>
        @endif
    @else
        <div class="bg-gray-50 border rounded-lg p-6 mb-6">
            <p class="text-gray-600 mb-4">
                この依頼に応募するにはログインが必要です
            </p>
            <a href="{{ route('login') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                ログインする
            </a>
        </div>
    @endauth

    {{-- レビューセクション --}}
    @if($job->reviews->count() > 0)
        <div class="bg-white border rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">レビュー</h2>
            <div class="space-y-4">
                @foreach($job->reviews as $review)
                    <div class="border-b pb-4 last:border-b-0 last:pb-0">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold">
                                    {{ $review->reviewer->name }} → {{ $review->reviewedUser->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $review->created_at->format('Y年m月d日') }}
                                </p>
                            </div>
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                {{ $review->rating_label }}
                            </span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-700 mt-2 whitespace-pre-wrap">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
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
                <div class="bg-white border rounded-lg p-6 mb-6">
                    <a href="{{ route('reviews.create', $job) }}" 
                       class="inline-block bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
                        レビューを投稿する
                    </a>
                </div>
            @endif
        @endif
    @endauth

    {{-- 画家の情報（任意） --}}
    @if($job->painter->painterProfile)
        <div class="bg-white border rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">投稿者情報</h2>
            <p class="text-gray-600">{{ $job->painter->name }}</p>
            @if($job->painter->painterProfile->bio)
                <p class="mt-2 text-gray-600">{{ $job->painter->painterProfile->bio }}</p>
            @endif
        </div>
    @endif
</div>
@endsection

