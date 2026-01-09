@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold mb-6">応募一覧</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600 mb-4">まだ応募がありません</p>
            <a href="{{ route('jobs.index') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                依頼を探す
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold mb-2">
                                <a href="{{ route('jobs.show', $application->job) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $application->job->title }}
                                </a>
                            </h2>
                            <div class="text-sm text-gray-600 mb-2">
                                投稿者: {{ $application->job->painter->name }}
                            </div>
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
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    キャンセル
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($application->message)
                        <div class="mb-4 p-3 bg-gray-50 rounded">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $application->message }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                        @if($application->job->reward_amount)
                            <div>
                                <span class="font-semibold">報酬:</span>
                                {{ number_format($application->job->reward_amount) }}円
                            </div>
                        @endif

                        <div>
                            <span class="font-semibold">場所:</span>
                            {{ $application->job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                        </div>

                        @if($application->job->scheduled_date)
                            <div>
                                <span class="font-semibold">日程:</span>
                                {{ $application->job->scheduled_date->format('Y/m/d') }}
                            </div>
                        @endif

                        <div>
                            <span class="font-semibold">応募日:</span>
                            {{ $application->created_at->format('Y/m/d') }}
                        </div>
                    </div>

                    @if($application->status === 'accepted')
                        <div class="flex gap-2">
                            <a href="{{ route('messages.show', ['job' => $application->job, 'with' => $application->job->painter_id]) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                メッセージを送る
                            </a>
                            <a href="{{ route('jobs.show', $application->job) }}" 
                               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                                依頼詳細を見る
                            </a>
                        </div>
                    @else
                        <a href="{{ route('jobs.show', $application->job) }}" 
                           class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                            依頼詳細を見る
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

