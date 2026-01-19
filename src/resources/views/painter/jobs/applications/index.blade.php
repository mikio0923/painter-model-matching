@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('painter.jobs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
            ← 依頼一覧に戻る
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-2">{{ $job->title }} - 応募者一覧</h1>
    <p class="text-gray-600 mb-6">応募者数: {{ $applications->count() }}件</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600">まだ応募がありません</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <div class="border rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start gap-4 flex-1">
                            @if($application->model->modelProfile && $application->model->modelProfile->profile_image_path)
                                <img src="{{ Storage::url($application->model->modelProfile->profile_image_path) }}" 
                                     alt="{{ $application->model->modelProfile->display_name }}" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1">
                                <h3 class="text-lg font-semibold mb-1">
                                    @if($application->model->modelProfile)
                                        <a href="{{ route('models.show', $application->model->modelProfile) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $application->model->modelProfile->display_name }}
                                        </a>
                                    @else
                                        {{ $application->model->name }}
                                    @endif
                                </h3>
                                
                                @if($application->model->modelProfile)
                                    <div class="text-sm text-gray-600 mb-2">
                                        @if($application->model->modelProfile->prefecture)
                                            <span>{{ $application->model->modelProfile->prefecture }}</span>
                                        @endif
                                        @if($application->model->modelProfile->age)
                                            <span class="ml-2">{{ $application->model->modelProfile->age }}歳</span>
                                        @endif
                                    </div>
                                @endif

                                @if($application->message)
                                    <div class="mt-3 p-3 bg-gray-50 rounded">
                                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $application->message }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="ml-4">
                            @if($application->status === 'applied')
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mb-2">
                                    応募中
                                </span>
                            @elseif($application->status === 'accepted')
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full mb-2">
                                    承認済み
                                </span>
                            @elseif($application->status === 'rejected')
                                <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full mb-2">
                                    却下
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t">
                        <div class="text-sm text-gray-500">
                            応募日: {{ $application->created_at->format('Y年m月d日 H:i') }}
                        </div>

                        <div class="flex gap-2">
                            @if($application->status === 'applied')
                                <form action="{{ route('painter.jobs.applications.accept', ['job' => $job, 'application' => $application]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm"
                                            onclick="return confirm('この応募を承認しますか？')">
                                        承認
                                    </button>
                                </form>
                                <form action="{{ route('painter.jobs.applications.reject', ['job' => $job, 'application' => $application]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm"
                                            onclick="return confirm('この応募を却下しますか？')">
                                        却下
                                    </button>
                                </form>
                            @endif

                            @if($application->status === 'accepted')
                                <a href="{{ route('messages.show', ['job' => $job, 'with' => $application->model_id]) }}" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                    メッセージを送る
                                </a>
                            @endif

                            @if($application->model->modelProfile)
                                <a href="{{ route('models.show', $application->model->modelProfile) }}" 
                                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                                    プロフィールを見る
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

