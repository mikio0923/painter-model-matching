@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold mb-6">マイページ</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- プロフィール情報 --}}
        <div class="md:col-span-2 bg-white border rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">プロフィール情報</h2>
            
            @if($painterProfile)
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">{{ $painterProfile->display_name }}</h3>
                    @if($painterProfile->prefecture)
                        <p class="text-sm text-gray-600 mt-1">{{ $painterProfile->prefecture }}</p>
                    @endif
                    @if($painterProfile->art_styles && count($painterProfile->art_styles) > 0)
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($painterProfile->art_styles as $style)
                                <span class="text-xs bg-gray-100 text-gray-700 rounded px-2 py-1">{{ $style }}</span>
                            @endforeach
                        </div>
                    @endif
                    @if($painterProfile->portfolio_url)
                        <p class="text-sm mt-2">
                            <a href="{{ $painterProfile->portfolio_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                ポートフォリオを見る →
                            </a>
                        </p>
                    @endif
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-4">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">プロフィールを作成しましょう</h3>
                    <p class="text-blue-700 text-sm mb-4">
                        画家として活動するために、まずプロフィールを作成してください。<br>
                        プロフィールを設定することで、モデルに依頼を出すことができます。
                    </p>
                    <a href="{{ route('painter.profile.edit') }}" 
                       class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-sm font-semibold">
                        プロフィールを作成する
                    </a>
                </div>
            @endif

            <div class="mt-4 flex gap-3">
                @if($painterProfile)
                    <a href="{{ route('painter.profile.edit') }}" 
                       class="inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                        プロフィールを編集
                    </a>
                @endif
                <a href="{{ route('painter.jobs.create') }}" 
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    新しい依頼を作成
                </a>
            </div>
        </div>

        {{-- クイックアクション --}}
        <div class="bg-white border rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">クイックアクション</h2>
            <div class="space-y-3">
                <a href="{{ route('painter.jobs.index') }}" 
                   class="block bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded p-3 text-sm">
                    <div class="font-semibold">依頼一覧</div>
                    <div class="text-xs text-gray-600 mt-1">自分の依頼を管理</div>
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
                <a href="{{ route('models.index') }}" 
                   class="block bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded p-3 text-sm">
                    <div class="font-semibold">モデルを探す</div>
                    <div class="text-xs text-gray-600 mt-1">モデルを検索</div>
                </a>
            </div>
        </div>
    </div>

    {{-- 統計情報 --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $totalJobs }}</div>
            <div class="text-sm text-gray-600 mt-1">総依頼数</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-green-600">{{ $openJobs }}</div>
            <div class="text-sm text-gray-600 mt-1">募集中</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $completedJobs }}</div>
            <div class="text-sm text-gray-600 mt-1">完了済み</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-yellow-600">{{ $totalApplications }}</div>
            <div class="text-sm text-gray-600 mt-1">総応募数</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-indigo-600">{{ $acceptedApplications }}</div>
            <div class="text-sm text-gray-600 mt-1">承認済み応募</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-pink-600">
                @if($averageRating)
                    {{ number_format($averageRating, 1) }}
                @else
                    -
                @endif
            </div>
            <div class="text-sm text-gray-600 mt-1">平均評価</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-red-600">{{ $unreadMessages }}</div>
            <div class="text-sm text-gray-600 mt-1">未読メッセージ</div>
        </div>
        <div class="bg-white border rounded-lg p-6">
            <div class="text-2xl font-bold text-teal-600">{{ $totalFavorites }}</div>
            <div class="text-sm text-gray-600 mt-1">お気に入り数</div>
        </div>
    </div>

    {{-- 最近の依頼 --}}
    <div class="bg-white border rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">最近の依頼</h2>
            <a href="{{ route('painter.jobs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                すべて見る →
            </a>
        </div>

        @if($jobs->isEmpty())
            <p class="text-gray-600 mb-4">まだ依頼がありません</p>
            <a href="{{ route('painter.jobs.create') }}" 
               class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                最初の依頼を作成
            </a>
        @else
            <div class="space-y-3">
                @foreach($jobs as $job)
                    <div class="border rounded p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold">
                                    <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $job->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                    <span>
                                        @if($job->status === 'open')
                                            <span class="text-green-600">募集中</span>
                                        @elseif($job->status === 'closed')
                                            <span class="text-gray-600">締切</span>
                                        @else
                                            <span class="text-blue-600">完了</span>
                                        @endif
                                    </span>
                                    <span>応募: {{ $job->applications()->count() }}件</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('painter.jobs.applications.index', $job) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    応募者を見る →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

