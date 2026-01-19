@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Pickup Model --}}
    @if($pickupModels->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Pickup Model / ピックアップモデル</h2>
                <a href="{{ route('models.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-3">
                @foreach($pickupModels as $model)
                    <a href="{{ route('models.show', $model) }}" class="block border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-[3/4] bg-gray-200 overflow-hidden">
                            @if($model->profile_image_path)
                                <img src="{{ Storage::url($model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-2">
                            <div class="font-semibold text-xs mb-1 truncate">{{ $model->display_name }}</div>
                            <div class="text-xs text-gray-600 truncate">
                                @if($model->prefecture){{ $model->prefecture }}@endif
                                @if($model->age) {{ $model->age }}歳@endif
                            </div>
                            @if($model->reward_min || $model->reward_max)
                                <div class="text-xs font-semibold text-blue-600 mt-1 truncate">
                                    {{ $model->reward_min ? number_format($model->reward_min) : '' }}円{{ $model->reward_max ? '〜' : '' }}
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Pickup Job --}}
    @if($pickupJobs->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Pickup Job / ピックアップジョブ</h2>
                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pickupJobs as $job)
                    <a href="{{ route('jobs.show', $job) }}" class="block border rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <h3 class="font-semibold text-lg mb-2">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ mb_strlen($job->description) > 100 ? mb_substr($job->description, 0, 100) . '...' : $job->description }}
                        </p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                @if($job->prefecture)
                                    ({{ $job->prefecture }})
                                @endif
                            </span>
                            @if($job->reward_amount)
                                <span class="font-semibold text-blue-600">
                                    {{ number_format($job->reward_amount) }}円
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Image Update Profile --}}
    @if($imageUpdateModels->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Image Update Profile / 画像更新されたプロフィール</h2>
                <a href="{{ route('models.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-10 gap-4">
                @foreach($imageUpdateModels as $model)
                    <a href="{{ route('models.show', $model) }}" class="block">
                        <div class="aspect-[3/4] bg-gray-200 rounded-lg overflow-hidden mb-2">
                            @if($model->profile_image_path)
                                <img src="{{ Storage::url($model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-sm">{{ $model->display_name }}</div>
                            <div class="text-xs text-gray-600">
                                @if($model->prefecture){{ $model->prefecture }}@endif
                                @if($model->age) {{ $model->age }}歳@endif
                            </div>
                            @if($model->reward_min || $model->reward_max)
                                <div class="text-xs font-semibold text-blue-600 mt-1">
                                    参考価格：{{ $model->reward_min ? number_format($model->reward_min) : '' }}円{{ $model->reward_max ? '〜' : '' }}
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 新着レビュー --}}
    @if($latestReviews->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">新着レビュー</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($latestReviews as $review)
                    <div class="border rounded-lg p-4 bg-white">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-sm">
                                    {{ $review->reviewer->name }} → {{ $review->reviewedUser->name }}
                                </p>
                                @if($review->job)
                                    <p class="text-xs text-gray-600 mt-1">
                                        <a href="{{ route('jobs.show', $review->job) }}" class="hover:text-blue-600">
                                            {{ $review->job->title }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $review->rating_label }}
                            </span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-700 mt-2 line-clamp-3">
                                {{ mb_strlen($review->comment) > 100 ? mb_substr($review->comment, 0, 100) . '...' : $review->comment }}
                            </p>
                        @endif
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $review->created_at->format('Y年m月d日') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 新着モデル --}}
    @if($models->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">新着モデル</h2>
                <a href="{{ route('models.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @foreach($models as $model)
                    <a href="{{ route('models.show', $model) }}" class="block border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-[3/4] bg-gray-200 overflow-hidden">
                            @if($model->profile_image_path)
                                <img src="{{ Storage::url($model->profile_image_path) }}"
                                     alt="{{ $model->display_name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 text-center">
                            <div class="font-semibold text-xs">{{ $model->display_name }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- 新着依頼 --}}
    @if($jobs->count() > 0)
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">新着依頼</h2>
                <a href="{{ route('jobs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    もっと見る →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($jobs as $job)
                    <a href="{{ route('jobs.show', $job) }}" class="block border rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <h3 class="font-semibold text-lg mb-2">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            {{ mb_strlen($job->description) > 100 ? mb_substr($job->description, 0, 100) . '...' : $job->description }}
                        </p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">
                                {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                @if($job->prefecture)
                                    ({{ $job->prefecture }})
                                @endif
                            </span>
                            @if($job->reward_amount)
                                <span class="font-semibold text-blue-600">
                                    {{ number_format($job->reward_amount) }}円
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection
