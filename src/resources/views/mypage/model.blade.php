@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold mb-6">マイページ</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- プロフィール情報 --}}
        <div class="md:col-span-2 bg-white border rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">プロフィール情報</h2>
            
            @if($modelProfile)
                <div class="flex items-start gap-4 mb-4">
                    @if($modelProfile->profile_image_path)
                        <img src="{{ Storage::url($modelProfile->profile_image_path) }}" 
                             alt="{{ $modelProfile->display_name }}" 
                             class="w-24 h-24 object-cover rounded-lg">
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold">{{ $modelProfile->display_name }}</h3>
                        <div class="text-sm text-gray-600 mt-1">
                            @if($modelProfile->prefecture){{ $modelProfile->prefecture }}@endif
                            @if($modelProfile->age) {{ $modelProfile->age }}歳@endif
                        </div>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div>
                        <span class="font-semibold">公開状態:</span>
                        {{ $modelProfile->is_public ? '公開中' : '非公開' }}
                    </div>
                    @if($modelProfile->reward_min || $modelProfile->reward_max)
                        <div>
                            <span class="font-semibold">報酬目安:</span>
                            {{ $modelProfile->reward_min ? number_format($modelProfile->reward_min) : '' }}円
                            {{ $modelProfile->reward_max ? '〜' . number_format($modelProfile->reward_max) : '' }}円
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('model.profile.edit') }}" 
                       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        プロフィールを編集
                    </a>
                </div>
            @else
                <p class="text-gray-600 mb-4">プロフィールがまだ作成されていません</p>
                <a href="{{ route('model.profile.edit') }}" 
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    プロフィールを作成
                </a>
            @endif
        </div>

        {{-- クイックアクション --}}
        <div class="bg-white border rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">クイックアクション</h2>
            <div class="space-y-3">
                <a href="{{ route('model.applications.index') }}" 
                   class="block bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded p-3 text-sm">
                    <div class="font-semibold">応募一覧</div>
                    <div class="text-xs text-gray-600 mt-1">応募した依頼を確認</div>
                </a>
                <a href="{{ route('messages.index') }}" 
                   class="block bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded p-3 text-sm relative">
                    <div class="font-semibold">メッセージ</div>
                    <div class="text-xs text-gray-600 mt-1">メッセージを確認</div>
                    @if($unreadMessages > 0)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                            {{ $unreadMessages }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('jobs.index') }}" 
                   class="block bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded p-3 text-sm">
                    <div class="font-semibold">依頼を探す</div>
                    <div class="text-xs text-gray-600 mt-1">新しい依頼を探す</div>
                </a>
            </div>
        </div>
    </div>

    {{-- 最近の応募 --}}
    <div class="bg-white border rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">最近の応募</h2>
            <a href="{{ route('model.applications.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                すべて見る →
            </a>
        </div>

        @if($applications->isEmpty())
            <p class="text-gray-600">まだ応募がありません</p>
        @else
            <div class="space-y-3">
                @foreach($applications as $application)
                    <div class="border rounded p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold">
                                    <a href="{{ route('jobs.show', $application->job) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $application->job->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    投稿者: {{ $application->job->painter->name }}
                                </p>
                            </div>
                            <div>
                                @if($application->status === 'applied')
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        応募中
                                    </span>
                                @elseif($application->status === 'accepted')
                                    <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        承認済み
                                    </span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        却下
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

