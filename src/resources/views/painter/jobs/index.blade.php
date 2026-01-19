@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">依頼一覧</h1>
        <a href="{{ route('painter.jobs.create') }}" 
           class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            新しい依頼を作成
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($jobs->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4">まだ依頼がありません</p>
            <a href="{{ route('painter.jobs.create') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                最初の依頼を作成する
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($jobs as $job)
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold mb-2">
                                <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $job->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 text-sm mb-2">
                                {{ mb_strlen($job->description) > 100 ? mb_substr($job->description, 0, 100) . '...' : $job->description }}
                            </p>
                        </div>
                        <div class="ml-4">
                            @if($job->status === 'open')
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    募集中
                                </span>
                            @elseif($job->status === 'closed')
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    締切
                                </span>
                            @else
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    完了
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-4">
                        @if($job->reward_amount)
                            <div>
                                <span class="font-semibold">報酬：</span>
                                {{ number_format($job->reward_amount) }}円
                                @if($job->reward_unit === 'per_hour')
                                    /時間
                                @else
                                    /回
                                @endif
                            </div>
                        @endif

                        <div>
                            <span class="font-semibold">場所：</span>
                            {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                            @if($job->prefecture)
                                ({{ $job->prefecture }})
                            @endif
                        </div>

                        @if($job->scheduled_date)
                            <div>
                                <span class="font-semibold">日程：</span>
                                {{ $job->scheduled_date->format('Y/m/d') }}
                            </div>
                        @endif

                        @if($job->apply_deadline)
                            <div>
                                <span class="font-semibold">締切：</span>
                                {{ $job->apply_deadline->format('Y/m/d') }}
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('jobs.show', $job) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            詳細を見る
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('painter.jobs.applications.index', $job) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            応募一覧
                            @if($job->applications()->count() > 0)
                                ({{ $job->applications()->count() }})
                            @endif
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('painter.jobs.edit', $job) }}" 
                           class="text-gray-600 hover:text-gray-800">
                            編集
                        </a>
                    </div>

                    <div class="text-xs text-gray-500 mt-2">
                        作成日：{{ $job->created_at->format('Y/m/d H:i') }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

