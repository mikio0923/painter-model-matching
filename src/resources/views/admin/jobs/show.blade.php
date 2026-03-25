@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">依頼詳細</h1>
    <a href="{{ route('admin.jobs.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- 基本情報 --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">基本情報</h2>
        </div>
        <div class="card-body">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-secondary-500">ID</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">タイトル</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">説明</dt>
                    <dd class="mt-1 text-sm text-secondary-900 whitespace-pre-wrap">{{ $job->description }}</dd>
                </div>
                @if($job->category)
                <div>
                    <dt class="text-sm font-medium text-secondary-500">カテゴリ</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->category }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-secondary-500">報酬</dt>
                    <dd class="mt-1 text-sm text-secondary-900">
                        {{ number_format($job->reward_amount) }}{{ $job->reward_unit }}
                    </dd>
                </div>
                @if($job->transportation_fee)
                <div>
                    <dt class="text-sm font-medium text-secondary-500">交通費</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->transportation_fee }}</dd>
                </div>
                @endif
                @if($job->costume_provided)
                <div>
                    <dt class="text-sm font-medium text-secondary-500">衣装</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->costume_provided }}</dd>
                </div>
                @endif
                @if($job->target)
                <div>
                    <dt class="text-sm font-medium text-secondary-500">募集対象</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->target }}</dd>
                </div>
                @endif
                @if($job->recruitment_number)
                <div>
                    <dt class="text-sm font-medium text-secondary-500">募集人数</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ number_format($job->recruitment_number) }}名</dd>
                </div>
                @endif
                @if($job->location_type === 'offline' && ($job->prefecture || $job->city || $job->address))
                <div>
                    <dt class="text-sm font-medium text-secondary-500">場所</dt>
                    <dd class="mt-1 text-sm text-secondary-900">
                        @if($job->prefecture){{ $job->prefecture }}@endif
                        @if($job->city) {{ $job->city }}@endif
                        @if($job->address)
                            <div class="mt-1">{{ $job->address }}</div>
                        @endif
                    </dd>
                </div>
                @endif
                @if($job->access)
                <div>
                    <dt class="text-sm font-medium text-secondary-500">アクセス</dt>
                    <dd class="mt-1 text-sm text-secondary-900 whitespace-pre-wrap">{{ $job->access }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-secondary-500">ステータス</dt>
                    <dd class="mt-1">
                        <span class="badge badge-{{ $job->status === 'open' ? 'success' : ($job->status === 'completed' ? 'secondary' : 'error') }}">
                            {{ $job->status_label }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">投稿者</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->painter->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">投稿日</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $job->created_at->format('Y年m月d日 H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- 応募情報 --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">応募情報</h2>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <p class="text-sm text-secondary-500">応募数: <span class="font-bold text-secondary-900">{{ $job->applications->count() }}</span></p>
            </div>
            @if($job->applications->count() > 0)
                <div class="space-y-3">
                    @foreach($job->applications as $application)
                        <div class="border-b border-secondary-200 pb-3 last:border-0">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-secondary-900">{{ $application->model->name }}</span>
                                <span class="badge badge-{{ $application->status === 'accepted' ? 'success' : ($application->status === 'rejected' ? 'error' : 'warning') }}">
                                    {{ $application->status_label }}
                                </span>
                            </div>
                            <div class="text-xs text-secondary-500 mt-1">
                                {{ $application->created_at->format('Y/m/d H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-secondary-500 text-sm">応募がありません</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-6">
    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" 
          onsubmit="return confirm('本当にこの依頼を削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-secondary bg-error-600 hover:bg-error-700 text-white">
            依頼を削除
        </button>
    </form>
</div>
@endsection

