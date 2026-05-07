@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8">
    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Jobs</p>
    <h1 class="font-display text-2xl font-semibold text-secondary-900">依頼管理</h1>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.jobs.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="keyword" class="block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Keyword</label>
            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
                   placeholder="タイトル・説明"
                   class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
        </div>
        <div>
            <label for="status" class="block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Status</label>
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
                    class="w-full px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Search
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
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Title</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Painter</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Posted</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                    @foreach($jobs as $job)
                        @php
                            $statusClass = match($job->status) {
                                'open'      => 'text-success-700',
                                'completed' => 'text-secondary-500',
                                default     => 'text-error-600',
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
                                <span class="text-[10px] tracking-[0.25em] uppercase {{ $statusClass }}">
                                    ● {{ $job->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                {{ $job->created_at->format('Y . n . j') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.jobs.show', $job) }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-700 hover:text-secondary-900 mr-4">Detail</a>
                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline"
                                      onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] tracking-[0.25em] uppercase text-error-600 hover:text-error-700">Delete</button>
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
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Jobs</p>
            <p class="text-secondary-500 text-sm">依頼が見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
