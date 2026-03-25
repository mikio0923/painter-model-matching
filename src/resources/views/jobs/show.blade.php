@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('jobs.index') }}" class="text-secondary-600 hover:text-secondary-900 text-sm">
            ← 依頼一覧に戻る
        </a>
    </div>

    {{-- タイトル・お気に入り --}}
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-secondary-900">{{ $job->title }}</h1>
        @auth
            @if($isFavorite)
                <form action="{{ route('favorites.destroy.job', $job) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-500 text-red-600 hover:bg-red-50 text-sm font-medium">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                        お気に入り解除
                    </button>
                </form>
            @else
                <form action="{{ route('favorites.store.job', $job) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-secondary-300 bg-white hover:bg-secondary-50 text-secondary-700 text-sm font-medium">
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Good（お気に入りに追加）
                    </button>
                </form>
            @endif
        @endauth
    </div>

    {{-- 依頼内容（本文） --}}
    <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 md:p-8">
            <div class="prose prose-secondary max-w-none">
                <div class="whitespace-pre-wrap text-secondary-700 leading-relaxed">{{ $job->description }}</div>
            </div>
            @if($job->usage_purpose)
                <div class="mt-6 pt-6 border-t border-secondary-200">
                    <h3 class="text-sm font-semibold text-secondary-800 mb-2">用途</h3>
                    <p class="text-secondary-700">{{ $job->usage_purpose }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- モデル募集要項（表形式） --}}
    <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
        <h2 class="px-6 py-4 bg-secondary-50 border-b border-secondary-200 text-lg font-bold text-secondary-900">モデル募集要項</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="divide-y divide-secondary-200">
                    @if($job->category)
                    <tr>
                        <th class="px-6 py-3 w-40 flex-shrink-0 bg-secondary-50 text-sm font-semibold text-secondary-700">カテゴリ</th>
                        <td class="px-6 py-3 text-secondary-900">{{ $job->category }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="px-6 py-3 w-40 flex-shrink-0 bg-secondary-50 text-sm font-semibold text-secondary-700">エリア</th>
                        <td class="px-6 py-3 text-secondary-900">
                            {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                            @if($job->prefecture)
                                <span class="text-secondary-600">（{{ $job->prefecture }}）</span>
                            @endif
                        </td>
                    </tr>
                    @if($job->scheduled_date)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">日時</th>
                        <td class="px-6 py-3 text-secondary-900">{{ $job->scheduled_date->format('Y年n月j日') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">場所</th>
                        <td class="px-6 py-3 text-secondary-900">
                            @if($job->location_type === 'online')
                                オンライン
                            @else
                                @if($job->prefecture){{ $job->prefecture }}@endif
                                @if($job->city) {{ $job->city }}@endif
                                @if(!$job->prefecture && !$job->city)—@endif
                            @endif
                        </td>
                    </tr>
                    @if($job->reward_amount)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">参考報酬金額</th>
                        <td class="px-6 py-3 text-secondary-900">
                            {{ number_format($job->reward_amount) }}円
                            @if($job->reward_unit === 'per_hour')/時間@else/回@endif
                        </td>
                    </tr>
                    @endif
                    @if($job->transportation_fee)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">交通費の支給</th>
                        <td class="px-6 py-3 text-secondary-900">{{ $job->transportation_fee }}</td>
                    </tr>
                    @endif
                    @if($job->costume_provided)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">衣装の提供</th>
                        <td class="px-6 py-3 text-secondary-900">{{ $job->costume_provided }}</td>
                    </tr>
                    @endif
                    @if($job->target)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">募集対象</th>
                        <td class="px-6 py-3 text-secondary-900">{{ $job->target }}</td>
                    </tr>
                    @endif
                    @if($job->recruitment_number)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">募集人数</th>
                        <td class="px-6 py-3 text-secondary-900">{{ number_format($job->recruitment_number) }}名</td>
                    </tr>
                    @endif
                    @if($job->apply_deadline)
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">募集期限</th>
                        <td class="px-6 py-3 text-secondary-900">{{ $job->apply_deadline->format('Y年n月j日') }}まで</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="px-6 py-3 bg-secondary-50 text-sm font-semibold text-secondary-700">投稿者</th>
                        <td class="px-6 py-3 text-secondary-900">
                            {{ $job->painter->painterProfile?->display_name ?? $job->painter->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 場所・アクセス --}}
    @if($job->location_type === 'offline' && ($job->prefecture || $job->city || $job->address || $job->access))
    <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
        <h2 class="px-6 py-4 bg-secondary-50 border-b border-secondary-200 text-lg font-bold text-secondary-900">場所・アクセス</h2>
        <div class="p-6 text-secondary-700">
            @if($job->prefecture){{ $job->prefecture }}@endif
            @if($job->city) {{ $job->city }}@endif
            @if($job->address)
                <div class="mt-2 text-secondary-800">{{ $job->address }}</div>
            @endif
            @if($job->access)
                <div class="mt-3 whitespace-pre-wrap text-sm text-secondary-600">{{ $job->access }}</div>
            @endif
        </div>
    </div>
    @endif

    {{-- ENTRY LIST / エントリーコメント --}}
    <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
        <h2 class="px-6 py-4 bg-secondary-50 border-b border-secondary-200 text-lg font-bold text-secondary-900">ENTRY LIST / エントリーコメント</h2>
        <div class="p-6">
            @if($job->applications->count() > 0)
                <div class="space-y-4">
                    @foreach($job->applications->sortByDesc('created_at') as $app)
                        <div class="border-b border-secondary-100 pb-4 last:border-b-0 last:pb-0">
                            <p class="text-sm text-secondary-500 mb-1">{{ $app->created_at->format('Y.m.d H:i') }}</p>
                            <p class="font-semibold text-secondary-900">
                                {{ $app->model->modelProfile?->display_name ?? $app->model->name }}
                                @if($app->model->modelProfile?->prefecture)
                                    <span class="text-secondary-600 font-normal text-sm">［{{ $app->model->modelProfile->prefecture }}］</span>
                                @endif
                            </p>
                            @if($app->message)
                                <p class="mt-2 text-secondary-700 whitespace-pre-wrap text-sm">{{ $app->message }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <p class="mt-4 text-sm text-secondary-500">{{ $job->applications->count() }} 件中 1-{{ $job->applications->count() }} 件</p>
            @else
                <p class="text-secondary-500">まだエントリーがありません。</p>
            @endif
        </div>
    </div>

    {{-- 応募フォーム（モデルユーザーのみ） --}}
    @auth
        @if(auth()->user()->role === 'model')
            @if($hasApplied)
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
                    <p class="text-amber-800 font-semibold">この依頼には既に応募済みです</p>
                </div>
            @else
                <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
                    <h2 class="px-6 py-4 bg-secondary-50 border-b border-secondary-200 text-lg font-bold text-secondary-900">この依頼に応募する</h2>
                    <div class="p-6">
                        <form action="{{ route('model.jobs.apply', $job) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-secondary-700 mb-1">メッセージ（任意）</label>
                                <textarea id="message" name="message" rows="5" placeholder="応募メッセージを入力してください"
                                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                応募する
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-secondary-50 border border-secondary-200 rounded-lg p-6 mb-8">
                <p class="text-secondary-600">モデルアカウントでログインすると応募できます</p>
            </div>
        @endif
    @else
        <div class="bg-secondary-50 border border-secondary-200 rounded-lg p-6 mb-8">
            <p class="text-secondary-600 mb-4">この依頼に応募するにはログインが必要です</p>
            <a href="{{ route('login-register') }}" class="inline-block px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg">
                ログインする
            </a>
        </div>
    @endauth

    {{-- 投稿者（画家）情報カード --}}
    <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
        <h2 class="px-6 py-4 bg-secondary-50 border-b border-secondary-200 text-lg font-bold text-secondary-900">投稿者</h2>
        <div class="p-6">
            @php
                $painter = $job->painter;
                $painterProfile = $painter->painterProfile;
                $painterName = $painterProfile?->display_name ?? $painter->name;
                $goodCount = $job->favorites()->count();
            @endphp
            <div class="flex flex-wrap gap-4">
                @if($painterProfile?->profile_image_path)
                    <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterName }}" class="w-16 h-16 rounded-full object-cover border-2 border-secondary-200">
                @else
                    <div class="w-16 h-16 rounded-full bg-secondary-200 flex items-center justify-center text-secondary-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                @endif
                <div>
                    <p class="font-bold text-secondary-900 text-lg">{{ $painterName }}</p>
                    <p class="text-sm text-secondary-600 mt-1">Good（{{ $goodCount }}）</p>
                    @if($painterProfile?->bio ?? false)
                        <p class="mt-3 text-secondary-700 text-sm leading-relaxed">{{ $painterProfile->bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- レビュー --}}
    @if($job->reviews->count() > 0)
        <div class="bg-white rounded-lg border border-secondary-200 shadow-sm overflow-hidden mb-8">
            <h2 class="px-6 py-4 bg-secondary-50 border-b border-secondary-200 text-lg font-bold text-secondary-900">レビュー</h2>
            <div class="p-6 space-y-4">
                @foreach($job->reviews as $review)
                    <div class="border-b border-secondary-100 pb-4 last:border-b-0 last:pb-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                            <p class="font-semibold text-secondary-900">
                                {{ $review->reviewer->painterProfile?->display_name ?? $review->reviewer->modelProfile?->display_name ?? $review->reviewer->name }} → {{ $review->reviewedUser->painterProfile?->display_name ?? $review->reviewedUser->modelProfile?->display_name ?? $review->reviewedUser->name }}
                            </p>
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                {{ $review->rating_label }}
                            </span>
                        </div>
                        <p class="text-sm text-secondary-500">{{ $review->created_at->format('Y年n月j日') }}</p>
                        @if($review->comment)
                            <p class="mt-2 text-secondary-700 text-sm whitespace-pre-wrap">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @auth
        @if($canReview && $reviewTarget)
            @php
                $existingReview = $job->reviews()
                    ->where('reviewer_id', Auth::id())
                    ->where('reviewed_user_id', $reviewTarget->id)
                    ->first();
            @endphp
            @if(!$existingReview)
                <div class="mb-8">
                    <a href="{{ route('reviews.create', $job) }}" class="inline-block px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        レビューを投稿する
                    </a>
                </div>
            @endif
        @endif
    @endauth
</div>
@endsection
