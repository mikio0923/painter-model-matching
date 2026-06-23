@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('jobs.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                依頼一覧
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current max-w-xs">{{ $job->title }}</span>
        </div>
        @php
            $painter       = $job->painter;
            $painterProfile = $painter->painterProfile;
            $painterName   = $painterProfile?->display_name ?? $painter->name;
            $goodCount     = $job->favorites()->count();
            $areaLabel     = $job->location_type === 'online' ? 'オンライン' : 'オフライン';
            $areaDetail    = trim(($job->prefecture ?? '') . ' ' . ($job->city ?? ''));
            $rewardLabel   = null;
            if ($job->reward_amount) {
                $rewardLabel = number_format($job->reward_amount) . '円' . ($job->reward_unit === 'per_hour' ? '/時間' : '/回');
            }
        @endphp
        <div class="flex flex-wrap items-center gap-2 mt-1">
            <span class="{{ $job->status === 'open' ? 'status-open' : 'status-closed' }}">{{ $job->status_label }}</span>
            @if($job->category)
                <span class="badge badge-secondary">{{ $job->category }}</span>
            @endif
        </div>
        <h1 class="page-header-title mt-3">{{ $job->title }}</h1>
        <p class="page-header-meta mt-2">投稿日：{{ $job->created_at->format('Y.m.d') }}</p>
    </div>
</div>

<div class="page">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ========== メインコンテンツ ========== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 依頼内容 --}}
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <h2 class="font-display text-xl font-bold text-secondary-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-primary-600 rounded-full inline-block"></span>
                        依頼内容
                    </h2>
                    <div class="prose-custom whitespace-pre-wrap text-secondary-700 leading-relaxed text-sm">{{ $job->description }}</div>
                    @if($job->usage_purpose)
                        <div class="mt-6 pt-6 border-t border-secondary-100 bg-primary-50/50 -mx-6 md:-mx-8 px-6 md:px-8 py-4 rounded-b-2xl">
                            <p class="text-xs font-semibold text-primary-700 uppercase tracking-wider mb-1">用途</p>
                            <p class="text-sm text-secondary-700">{{ $job->usage_purpose }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 募集要項テーブル --}}
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-secondary-100 flex items-center gap-2">
                    <span class="w-1 h-5 bg-accent-500 rounded-full inline-block"></span>
                    <h2 class="font-display text-lg font-bold text-secondary-900">モデル募集要項</h2>
                </div>
                <div class="divide-y divide-secondary-100">
                    @php
                    $requirements = [
                        ['label' => 'エリア', 'value' => $areaLabel . ($job->prefecture ? '（' . $job->prefecture . '）' : '')],
                        ['label' => '日時', 'value' => $job->scheduled_date ? $job->scheduled_date->format('Y年n月j日') : null],
                        ['label' => '場所', 'value' => $job->location_type === 'online' ? 'オンライン' : ($areaDetail ?: '—')],
                        ['label' => '参考報酬', 'value' => $rewardLabel],
                        ['label' => '交通費', 'value' => $job->transportation_fee],
                        ['label' => '衣装提供', 'value' => $job->costume_provided],
                        ['label' => '募集対象', 'value' => $job->target],
                        ['label' => '募集人数', 'value' => $job->recruitment_number ? number_format($job->recruitment_number) . '名' : null],
                        ['label' => '応募期限', 'value' => $job->apply_deadline ? $job->apply_deadline->format('Y年n月j日') . 'まで' : null],
                        ['label' => '投稿者', 'value' => $painterName],
                    ];
                    @endphp
                    @foreach($requirements as $row)
                        @if($row['value'])
                        <div class="flex items-start px-6 py-3.5">
                            <dt class="w-32 shrink-0 text-xs font-semibold text-secondary-500 uppercase tracking-wide pt-0.5">{{ $row['label'] }}</dt>
                            <dd class="text-sm text-secondary-800 flex-1">{{ $row['value'] }}</dd>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- 場所・アクセス --}}
            @if($job->location_type === 'offline' && ($job->prefecture || $job->city || $job->address || $job->access))
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-secondary-100 flex items-center gap-2">
                    <span class="w-1 h-5 bg-gold-500 rounded-full inline-block"></span>
                    <h2 class="font-display text-lg font-bold text-secondary-900">場所・アクセス</h2>
                </div>
                <div class="p-6 text-sm text-secondary-700 leading-relaxed">
                    <p class="font-medium text-secondary-900">
                        @if($job->prefecture){{ $job->prefecture }}@endif
                        @if($job->city) {{ $job->city }}@endif
                    </p>
                    @if($job->address)<p class="mt-2">{{ $job->address }}</p>@endif
                    @if($job->access)<p class="mt-3 text-secondary-500 whitespace-pre-wrap">{{ $job->access }}</p>@endif
                </div>
            </div>
            @endif

            {{-- エントリーコメント --}}
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-secondary-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-1 h-5 bg-success-500 rounded-full inline-block"></span>
                        <h2 class="font-display text-lg font-bold text-secondary-900">エントリーコメント</h2>
                    </div>
                    <span class="badge badge-secondary">{{ $job->applications->count() }}件</span>
                </div>
                <div class="p-6">
                    @if($job->applications->count() > 0)
                        <div class="space-y-5">
                            @foreach($job->applications->sortByDesc('created_at') as $app)
                            <div class="flex gap-3">
                                <div class="avatar avatar-sm border border-secondary-200 shrink-0">
                                    <svg class="w-3.5 h-3.5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline gap-2 mb-1">
                                        <span class="text-sm font-semibold text-secondary-900">
                                            {{ $app->model->modelProfile?->display_name ?? $app->model->name }}
                                        </span>
                                        @if($app->model->modelProfile?->prefecture)
                                            <span class="text-xs text-secondary-400">{{ $app->model->modelProfile->prefecture }}</span>
                                        @endif
                                        <span class="text-xs text-secondary-400 ml-auto">{{ $app->created_at->format('Y.m.d H:i') }}</span>
                                    </div>
                                    @if($app->message)
                                        <p class="text-sm text-secondary-600 leading-relaxed whitespace-pre-wrap">{{ $app->message }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-secondary-400 text-center py-8">まだエントリーがありません</p>
                    @endif
                </div>
            </div>

            {{-- 応募フォーム --}}
            @auth
                @if(auth()->user()->role === 'model')
                    @if($hasApplied)
                        <div class="bg-gold-50 border border-gold-200 rounded-2xl p-6 flex items-center gap-3">
                            <svg class="w-5 h-5 text-gold-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-semibold text-gold-800">この依頼には既に応募済みです</p>
                        </div>
                    @else
                        <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-secondary-100 flex items-center gap-2">
                                <span class="w-1 h-5 bg-primary-600 rounded-full inline-block"></span>
                                <h2 class="font-display text-lg font-bold text-secondary-900">この依頼に応募する</h2>
                            </div>
                            <div class="p-6">
                                <form action="{{ route('model.jobs.apply', $job) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="message" class="form-label">メッセージ <span class="text-secondary-400 font-normal">（任意）</span></label>
                                        <textarea id="message" name="message" rows="5"
                                                  placeholder="自己紹介やこの依頼への意気込みを書いてください"
                                                  class="form-textarea"></textarea>
                                    </div>
                                    <button type="submit" class="btn-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        応募する
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-secondary-50 rounded-2xl border border-secondary-200 p-6">
                        <p class="text-sm text-secondary-500">モデルアカウントでログインすると応募できます</p>
                    </div>
                @endif
            @else
                <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-8 text-center">
                    <p class="text-secondary-600 mb-4 font-medium">この依頼に応募するにはログインが必要です</p>
                    <a href="{{ route('login-register') }}" class="btn-primary">
                        ログインして応募する
                    </a>
                </div>
            @endauth

            {{-- レビュー --}}
            @if($job->reviews->count() > 0)
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-secondary-100 flex items-center gap-2">
                    <span class="w-1 h-5 bg-gold-500 rounded-full inline-block"></span>
                    <h2 class="font-display text-lg font-bold text-secondary-900">レビュー</h2>
                </div>
                <div class="p-6 space-y-5">
                    @foreach($job->reviews as $review)
                    <div class="flex gap-4 pb-5 border-b border-secondary-100 last:border-b-0 last:pb-0">
                        <div class="avatar avatar-sm border border-secondary-200 shrink-0">
                            <svg class="w-3.5 h-3.5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-secondary-900">
                                    {{ $review->reviewer->painterProfile?->display_name ?? $review->reviewer->modelProfile?->display_name ?? $review->reviewer->name }}
                                    <span class="text-secondary-400 font-normal">→</span>
                                    {{ $review->reviewedUser->painterProfile?->display_name ?? $review->reviewedUser->modelProfile?->display_name ?? $review->reviewedUser->name }}
                                </span>
                                <x-star-rating :rating="(int) $review->rating" size="sm" />
                                <span class="text-xs text-secondary-400 ml-auto">{{ $review->created_at->format('Y年n月j日') }}</span>
                            </div>
                            @if($review->comment)
                                <p class="text-sm text-secondary-600 leading-relaxed whitespace-pre-wrap">{{ $review->comment }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @auth
                {{-- ───────── 取引完了 & レビュー ───────── --}}
                @if($acceptedApplication)
                    @php
                        $existingReview = $reviewTarget
                            ? $job->reviews()->where('reviewer_id', Auth::id())->where('reviewed_user_id', $reviewTarget->id)->first()
                            : null;
                        $isModel = auth()->user()->role === 'model';
                    @endphp

                    <div class="bg-canvas-50 border border-secondary-200 rounded-xl p-5 space-y-4">
                        <p class="text-xs font-semibold text-secondary-400 uppercase tracking-wider">取引ステータス</p>

                        @if($acceptedApplication->isCompleted())
                            <p class="text-sm text-success-700">
                                ✓ 取引完了済み（{{ $acceptedApplication->payment_received_at->format('Y/m/d') }}）
                            </p>
                        @elseif($isModel)
                            {{-- モデル: 取引完了の宣言フォーム --}}
                            <p class="text-sm text-secondary-700 leading-relaxed">
                                撮影が完了し、報酬を受け取り次第、下のボタンで取引完了を確定してください。完了するとレビューが投稿できます。
                            </p>
                            <form action="{{ route('model.applications.complete', $acceptedApplication) }}" method="POST"
                                  onsubmit="return confirm('報酬を受け取ったとして取引を完了します。よろしいですか？');">
                                @csrf
                                <button type="submit"
                                        @disabled(!$canMarkComplete)
                                        class="px-6 py-2.5 bg-success-600 text-white text-sm font-medium border border-success-600 hover:bg-success-700 transition-colors {{ $canMarkComplete ? '' : 'opacity-50 cursor-not-allowed' }}">
                                    報酬を受け取りました（取引完了）
                                </button>
                            </form>
                            @if(!$canMarkComplete && $job->scheduled_date)
                                <p class="text-xs text-secondary-500">
                                    取引完了は撮影日（{{ $job->scheduled_date->format('Y/m/d') }}）当日以降に行えます。
                                </p>
                            @endif
                        @else
                            {{-- 画家: モデルの取引完了承認待ち --}}
                            <p class="text-sm text-secondary-700 leading-relaxed">
                                モデルが報酬を受領し、取引完了を確定するとレビューを投稿できるようになります。
                            </p>
                        @endif

                        {{-- レビューボタン: 取引完了済かつ未投稿のときのみ活性 --}}
                        @if($reviewTarget)
                            <div class="pt-3 border-t border-secondary-100">
                                @if($existingReview)
                                    <div class="flex items-center gap-2 text-sm text-secondary-500">
                                        <span>レビュー投稿済み</span>
                                        <x-star-rating :rating="(int) $existingReview->rating" size="sm" />
                                    </div>
                                @elseif($canReview)
                                    <a href="{{ route('reviews.create', $job) }}" class="btn-accent">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.16c.969 0 1.371 1.24.588 1.81l-3.366 2.446a1 1 0 00-.364 1.118l1.286 3.957c.299.921-.755 1.688-1.538 1.118l-3.366-2.446a1 1 0 00-1.176 0L5.745 17.02c-.783.57-1.837-.197-1.538-1.118l1.286-3.957a1 1 0 00-.364-1.118L1.763 9.384c-.783-.57-.38-1.81.588-1.81h4.16a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                        レビューを投稿する
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-2 px-5 py-2.5 border border-secondary-300 text-secondary-400 text-sm cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.16c.969 0 1.371 1.24.588 1.81l-3.366 2.446a1 1 0 00-.364 1.118l1.286 3.957c.299.921-.755 1.688-1.538 1.118l-3.366-2.446a1 1 0 00-1.176 0L5.745 17.02c-.783.57-1.837-.197-1.538-1.118l1.286-3.957a1 1 0 00-.364-1.118L1.763 9.384c-.783-.57-.38-1.81.588-1.81h4.16a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                        レビューを投稿する（取引完了後に活性化）
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            @endauth

        </div>

        {{-- ========== サイドバー ========== --}}
        <div class="space-y-5 lg:sticky lg:top-24 lg:self-start">

            {{-- 概要カード --}}
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="p-5 space-y-3">
                    @if($rewardLabel)
                    <div class="text-center py-4 border-b border-secondary-100">
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Reward</p>
                        <p class="text-xl font-medium text-secondary-900">{{ $rewardLabel }}</p>
                    </div>
                    @endif
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex items-start justify-between gap-2">
                            <dt class="text-secondary-400 shrink-0">エリア</dt>
                            <dd class="font-medium text-secondary-800 text-right">{{ $areaLabel }}@if($job->prefecture)（{{ $job->prefecture }}）@endif</dd>
                        </div>
                        @if($job->scheduled_date)
                        <div class="flex items-start justify-between gap-2">
                            <dt class="text-secondary-400 shrink-0">日時</dt>
                            <dd class="font-medium text-secondary-800">{{ $job->scheduled_date->format('Y年n月j日') }}</dd>
                        </div>
                        @endif
                        @if($job->apply_deadline)
                        <div class="flex items-start justify-between gap-2">
                            <dt class="text-secondary-400 shrink-0">募集期限</dt>
                            <dd class="font-medium text-secondary-800">{{ $job->apply_deadline->format('Y年n月j日') }}</dd>
                        </div>
                        @endif
                        <div class="flex items-start justify-between gap-2">
                            <dt class="text-secondary-400 shrink-0">エントリー</dt>
                            <dd class="font-semibold text-secondary-800">{{ number_format($job->applications->count()) }}件</dd>
                        </div>
                    </dl>
                </div>

                {{-- お気に入りボタン（自分の依頼には表示しない） --}}
                @auth
                @if(auth()->id() !== $job->painter_id)
                <div class="px-5 pb-5">
                    <x-favorite-button
                        type="job"
                        :id="$job->id"
                        :favorited="$isFavorite"
                        variant="large"
                        color-on="red"
                        label-on="お気に入り"
                        label-off="お気に入り" />
                </div>
                @endif
                @endauth
            </div>

            {{-- 投稿者カード --}}
            <div class="bg-canvas-50 rounded-xl border border-secondary-200 overflow-hidden">
                <div class="p-5">
                    <p class="text-xs font-semibold text-secondary-400 uppercase tracking-wider mb-4">投稿者</p>
                    @php
                        $painterProfileLink = $painterProfile ? route('painters.show', $painterProfile) : null;
                    @endphp
                    <div class="flex items-center gap-3">
                        @if($painterProfileLink)
                            <a href="{{ $painterProfileLink }}"
                               class="avatar avatar-lg border-2 border-primary-100 shrink-0 hover:border-primary-300 transition-colors"
                               title="{{ $painterName }} のプロフィールを見る">
                                @if($painterProfile?->profile_image_path)
                                    <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterName }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-7 h-7 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @endif
                            </a>
                        @else
                            <div class="avatar avatar-lg border-2 border-primary-100 shrink-0">
                                <svg class="w-7 h-7 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                        <div class="min-w-0">
                            @if($painterProfileLink)
                                <a href="{{ $painterProfileLink }}"
                                   class="font-bold text-secondary-900 truncate hover:text-primary-600 transition-colors">
                                    {{ $painterName }}
                                </a>
                            @else
                                <p class="font-bold text-secondary-900 truncate">{{ $painterName }}</p>
                            @endif
                            <p class="text-xs text-secondary-400 mt-0.5">画家</p>
                        </div>
                    </div>
                    @if($painterProfile?->bio)
                        <p class="mt-4 text-sm text-secondary-600 leading-relaxed line-clamp-4">{{ $painterProfile->bio }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
