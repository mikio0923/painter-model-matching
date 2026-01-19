@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- ========== ピックアップモデル欄 ========== --}}
    @if($pickupModels->count() > 0)
        <section class="mb-16 pb-12 border-b border-secondary-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title">Pickup Model / ピックアップモデル</h2>
                <a href="{{ route('models.index') }}" class="link-primary text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($pickupModels as $model)
                    <a href="{{ route('models.show', $model) }}" class="card overflow-hidden">
                        {{-- 画像 --}}
                        <div class="aspect-[3/4] bg-secondary-200 overflow-hidden">
                            @if($model->profile_image_path)
                                <img src="{{ asset('storage/' . $model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- 情報 --}}
                        <div class="card-body">
                            <div class="font-semibold text-lg mb-1 text-secondary-900">
                                {{ $model->display_name }}
                            </div>

                            <div class="text-sm text-secondary-600 mb-2">
                                @if($model->prefecture)
                                    <span>{{ $model->prefecture }}</span>
                                @endif

                                @if($model->age)
                                    <span class="ml-2">{{ $model->age }}歳</span>
                                @endif

                                @if($model->gender)
                                    <span class="ml-2">
                                        @if($model->gender === 'male')男性
                                        @elseif($model->gender === 'female')女性
                                        @elseその他
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @if($model->reward_min || $model->reward_max)
                                <div class="text-sm font-semibold text-primary-600 mb-2">
                                    参考価格：
                                    @if($model->reward_min && $model->reward_max)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_min)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_max)
                                        〜{{ number_format($model->reward_max) }}円
                                    @endif
                                </div>
                            @endif

                            @php $tags = $model->style_tags ?? []; @endphp
                            @if(count($tags) > 0)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach(array_slice($tags, 0, 3) as $tag)
                                        <span class="badge badge-secondary">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($tags) > 3)
                                        <span class="text-xs text-secondary-500">+{{ count($tags) - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ========== モデル募集欄 ========== --}}
    @if($pickupJobs->count() > 0)
        <section class="mb-16 pb-12 border-b border-secondary-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title">Pickup Job / ピックアップジョブ</h2>
                <a href="{{ route('jobs.index') }}" class="link-primary text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pickupJobs as $job)
                    <a href="{{ route('jobs.show', $job) }}" class="card">
                        <div class="card-body">
                            <h3 class="font-semibold text-lg mb-2 text-secondary-900">{{ $job->title }}</h3>
                            <p class="text-sm text-secondary-600 mb-3 line-clamp-2">
                                {{ mb_strlen($job->description) > 100 ? mb_substr($job->description, 0, 100) . '...' : $job->description }}
                            </p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-secondary-600">
                                    {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                    @if($job->prefecture)
                                        ({{ $job->prefecture }})
                                    @endif
                                </span>
                                @if($job->reward_amount)
                                    <span class="font-semibold text-primary-600">
                                        {{ number_format($job->reward_amount) }}円
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ========== 新着レビュー欄 ========== --}}
    @if($latestReviews->count() > 0)
        <section class="mb-16 pb-12 border-b border-secondary-200">
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title">Review / 新着レビュー</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($latestReviews as $review)
                    <div class="card">
                        <div class="card-body">
                            {{-- 評価のレベル --}}
                            <div class="mb-3">
                                <span class="badge badge-success">
                                    {{ $review->rating_label }}
                                </span>
                            </div>

                            {{-- 評価者の名前 --}}
                            <div class="mb-3">
                                <p class="font-semibold text-sm text-secondary-900">
                                    {{ $review->reviewer->name }}
                                    @if($review->reviewer->role === 'model')
                                        <span class="text-xs text-secondary-600">（モデル）</span>
                                    @else
                                        <span class="text-xs text-secondary-600">（画家）</span>
                                    @endif
                                </p>
                            </div>

                            {{-- 評価内容 --}}
                            @if($review->comment)
                                <p class="text-sm text-secondary-700 leading-relaxed line-clamp-4">
                                    {{ $review->comment }}
                                </p>
                            @else
                                <p class="text-sm text-secondary-500 italic">
                                    コメントなし
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Image Update Profile --}}
    @if($imageUpdateModels->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title">Image Update Profile / 画像更新されたプロフィール</h2>
                <a href="{{ route('models.index') }}" class="link-primary text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="w-full max-w-4xl mx-auto px-4">
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                    @foreach($imageUpdateModels as $model)
                        <a href="{{ route('models.show', $model) }}" class="card p-1.5">
                            <div class="w-full aspect-square bg-secondary-200 rounded overflow-hidden mb-1.5">
                                @if($model->profile_image_path)
                                    <img src="{{ asset('storage/' . $model->profile_image_path) }}"
                                         alt="{{ $model->display_name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center">
                                <div class="text-xs text-secondary-600 mb-0.5 line-clamp-1">
                                    @if($model->prefecture){{ $model->prefecture }}@endif
                                    @if($model->age) {{ $model->age }}歳@endif
                                    @if($model->gender)
                                        @if($model->gender === 'male')(男性)
                                        @elseif($model->gender === 'female')(女性)
                                        @else(その他)
                                        @endif
                                    @endif
                                </div>
                                <div class="font-semibold text-xs mb-0.5 line-clamp-1 text-secondary-900">{{ $model->display_name }}</div>
                                @if($model->reward_min || $model->reward_max)
                                    <div class="text-xs font-semibold text-primary-600 line-clamp-1">
                                        参考価格：{{ $model->reward_min ? number_format($model->reward_min) : '' }}円{{ $model->reward_max ? '〜' : '' }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 新着モデル --}}
    @if($models->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="section-title">新着モデル</h2>
                <a href="{{ route('models.index') }}" class="link-primary text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($models as $model)
                    <a href="{{ route('models.show', $model) }}" class="card overflow-hidden">
                        {{-- 画像 --}}
                        <div class="aspect-[3/4] bg-secondary-200 overflow-hidden">
                            @if($model->profile_image_path)
                                <img src="{{ asset('storage/' . $model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-secondary-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- 情報 --}}
                        <div class="card-body">
                            <div class="font-semibold text-lg mb-1 text-secondary-900">
                                {{ $model->display_name }}
                            </div>

                            <div class="text-sm text-secondary-600 mb-2">
                                @if($model->prefecture)
                                    <span>{{ $model->prefecture }}</span>
                                @endif

                                @if($model->age)
                                    <span class="ml-2">{{ $model->age }}歳</span>
                                @endif

                                @if($model->gender)
                                    <span class="ml-2">
                                        @if($model->gender === 'male')男性
                                        @elseif($model->gender === 'female')女性
                                        @elseその他
                                        @endif
                                    </span>
                                @endif
                            </div>

                            @if($model->reward_min || $model->reward_max)
                                <div class="text-sm font-semibold text-primary-600 mb-2">
                                    参考価格：
                                    @if($model->reward_min && $model->reward_max)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_min)
                                        {{ number_format($model->reward_min) }}円〜
                                    @elseif($model->reward_max)
                                        〜{{ number_format($model->reward_max) }}円
                                    @endif
                                </div>
                            @endif

                            @php $tags = $model->style_tags ?? []; @endphp
                            @if(count($tags) > 0)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach(array_slice($tags, 0, 3) as $tag)
                                        <span class="badge badge-secondary">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($tags) > 3)
                                        <span class="text-xs text-secondary-500">+{{ count($tags) - 3 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif


    {{-- インフォメーションと新着オファー --}}
    <div class="flex flex-row gap-8 mb-12">
        {{-- 左カラム: お知らせとプレスリリース（縦に並べる） --}}
        <div class="flex flex-col space-y-8 flex-1 min-w-0">
            {{-- INFORMATION / お知らせ --}}
            @if($informations->count() > 0)
                <section class="block">
                    <h2 class="text-xl font-bold text-secondary-900 mb-4">> INFORMATION / お知らせ</h2>
                    <div class="space-y-2">
                        @foreach($informations as $info)
                            <div>
                                <a href="{{ route('information.show', $info) }}" class="link-primary underline">
                                    {{ $info->published_at->format('Y.m.d') }} {{ $info->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('information.index', ['type' => 'information']) }}" class="link-primary text-sm">
                            すべて表示
                        </a>
                    </div>
                </section>
            @endif

            {{-- FASHION PRESS / プレスリリース --}}
            @if($pressReleases->count() > 0)
                <section>
                    <h2 class="text-xl font-bold text-secondary-900 mb-4">> FASHION PRESS / プレスリリース</h2>
                    <div class="space-y-2">
                        @foreach($pressReleases as $press)
                            <div>
                                <a href="{{ route('information.show', $press) }}" class="link-primary underline">
                                    {{ $press->published_at->format('Y年m月d日') }} {{ $press->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- 右カラム: 新着オファー（左側の高さに合わせる） --}}
        <div class="flex-1 min-w-0">
            @if($newJobOffers->count() > 0)
                <section>
                    <h2 class="text-xl font-bold text-secondary-900 mb-4">> JOB OFFER / 新着オファー</h2>
                    <div class="flex flex-col space-y-1.5">
                        @foreach($newJobOffers as $job)
                            <div>
                                <a href="{{ route('jobs.show', $job) }}" class="link-primary block">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-sm text-secondary-600 whitespace-nowrap">{{ $job->created_at->format('Y.m.d H:i') }}</span>
                                        <span class="text-secondary-900">{{ $job->title }}</span>
                                        <span class="text-secondary-500 whitespace-nowrap">({{ $job->applications()->count() }})</span>
                                    </div>
                                    @if($job->painter && $job->painter->painterProfile)
                                        <div class="text-xs text-secondary-600 mt-0.5">
                                            {{ $job->painter->painterProfile->display_name }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('jobs.index') }}" class="link-primary text-sm">
                            すべて表示
                        </a>
                    </div>
                </section>
            @endif
        </div>
    </div>

</div>
@endsection
