@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8">
    <h1 class="font-display text-2xl font-semibold text-secondary-900">依頼管理</h1>
    <p class="text-sm text-secondary-500 mt-1">投稿された依頼の確認・削除ができます。</p>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.jobs.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="keyword" class="block text-sm font-medium text-secondary-700 mb-1.5">キーワード</label>
            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
                   placeholder="タイトル・説明"
                   class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-secondary-700 mb-1.5">ステータス</label>
            <select id="status" name="status"
                    class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                <option value="">すべて</option>
                <option value="open"      {{ request('status') === 'open'      ? 'selected' : '' }}>公開中</option>
                <option value="closed"    {{ request('status') === 'closed'    ? 'selected' : '' }}>締切</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>完了</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                検索する
            </button>
        </div>
    </div>
</form>

<div class="border border-secondary-200 bg-canvas-50">
    @if($jobs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">ID</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">タイトル</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">投稿者（画家）</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">ステータス</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">投稿日</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                    @foreach($jobs as $job)
                        @php
                            $statusInfo = match($job->status) {
                                'open'      => ['border' => 'border-success-500',   'bg' => 'bg-success-50',   'text' => 'text-success-700'],
                                'completed' => ['border' => 'border-secondary-300', 'bg' => 'bg-secondary-50', 'text' => 'text-secondary-600'],
                                default     => ['border' => 'border-error-500',     'bg' => 'bg-error-50',     'text' => 'text-error-700'],
                            };
                        @endphp
                        <tr class="hover:bg-secondary-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $job->id }}</td>
                            <td class="px-5 py-4 text-sm">
                                <a href="{{ route('admin.jobs.show', $job) }}" class="text-secondary-900 hover:text-secondary-700 transition-colors">
                                    {{ $job->title }}
                                </a>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-600">
                                {{ $job->painter->name }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 border {{ $statusInfo['border'] }} {{ $statusInfo['bg'] }} text-xs font-medium {{ $statusInfo['text'] }}">
                                    {{ $job->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">
                                {{ $job->created_at->format('Y/n/j') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.jobs.show', $job) }}" class="text-sm text-secondary-700 hover:text-secondary-900 mr-4">詳細</a>
                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline"
                                      onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-error-600 hover:text-error-700">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-secondary-200">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <p class="text-secondary-500">依頼が見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
