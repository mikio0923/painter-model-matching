@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8 flex items-end justify-between gap-4 flex-wrap">
    <div>
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Information</p>
        <h1 class="font-display text-2xl font-semibold text-secondary-900">お知らせ管理</h1>
    </div>
    <a href="{{ route('admin.information.create') }}"
       class="px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
        New
    </a>
</div>

<form method="GET" action="{{ route('admin.information.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="type" class="block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Type</label>
            <select id="type" name="type"
                    class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                <option value="">すべて</option>
                <option value="information"   {{ request('type') === 'information'   ? 'selected' : '' }}>お知らせ</option>
                <option value="press_release" {{ request('type') === 'press_release' ? 'selected' : '' }}>プレスリリース</option>
            </select>
        </div>
        <div>
            <label for="is_published" class="block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Published</label>
            <select id="is_published" name="is_published"
                    class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                <option value="">すべて</option>
                <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>公開</option>
                <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>非公開</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Filter
            </button>
        </div>
    </div>
</form>

<div class="border border-secondary-200 bg-canvas-50">
    @if($informations->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Title</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Type</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Published</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Date</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                    @foreach($informations as $information)
                        <tr class="hover:bg-secondary-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $information->id }}</td>
                            <td class="px-5 py-4 text-sm">
                                <a href="{{ route('admin.information.edit', $information) }}" class="text-secondary-900 hover:text-secondary-700 transition-colors">
                                    {{ $information->title }}
                                </a>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-600">
                                {{ $information->type === 'information' ? 'お知らせ' : 'プレスリリース' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-[10px] tracking-[0.25em] uppercase {{ $information->is_published ? 'text-success-700' : 'text-secondary-500' }}">
                                    ● {{ $information->is_published ? '公開' : '非公開' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                {{ $information->published_at ? $information->published_at->format('Y . n . j') : '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.information.edit', $information) }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-700 hover:text-secondary-900 mr-4">Edit</a>
                                <form action="{{ route('admin.information.destroy', $information) }}" method="POST" class="inline"
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
            {{ $informations->links() }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Information</p>
            <p class="text-secondary-500 text-sm">お知らせが見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
