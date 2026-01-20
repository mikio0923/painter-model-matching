@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">依頼管理</h1>
</div>

{{-- 検索フォーム --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.jobs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="keyword" class="form-label">キーワード</label>
                    <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" 
                           placeholder="タイトル・説明" class="form-input">
                </div>
                <div>
                    <label for="status" class="form-label">ステータス</label>
                    <select id="status" name="status" class="form-input">
                        <option value="">すべて</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>公開中</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>締切</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>完了</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">検索</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- 依頼一覧 --}}
<div class="card">
    <div class="card-body">
        @if($jobs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">タイトル</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">投稿者</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ステータス</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">投稿日</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @foreach($jobs as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">{{ $job->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ $job->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">
                                    {{ $job->painter->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-{{ $job->status === 'open' ? 'success' : ($job->status === 'completed' ? 'secondary' : 'error') }}">
                                        {{ $job->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">
                                    {{ $job->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-primary-600 hover:text-primary-700 mr-3">詳細</a>
                                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error-600 hover:text-error-700">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $jobs->links() }}
            </div>
        @else
            <p class="text-secondary-500 text-center py-8">依頼が見つかりませんでした</p>
        @endif
    </div>
</div>
@endsection

