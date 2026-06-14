@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8 flex items-end justify-between gap-4 flex-wrap">
    <div>
        <h1 class="font-display text-2xl font-semibold text-secondary-900">お知らせ管理</h1>
        <p class="text-sm text-secondary-500 mt-1">サイト掲載のお知らせ・プレスリリースを管理します。</p>
    </div>
    <a href="{{ route('admin.information.create') }}"
       class="inline-flex items-center gap-2 px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        新規作成
    </a>
</div>

<form method="GET" action="{{ route('admin.information.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="type" class="block text-sm font-medium text-secondary-700 mb-1.5">種別</label>
            <select id="type" name="type"
                    class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                <option value="">すべて</option>
                <option value="information"   {{ request('type') === 'information'   ? 'selected' : '' }}>お知らせ</option>
                <option value="press_release" {{ request('type') === 'press_release' ? 'selected' : '' }}>プレスリリース</option>
            </select>
        </div>
        <div>
            <label for="is_published" class="block text-sm font-medium text-secondary-700 mb-1.5">公開状態</label>
            <select id="is_published" name="is_published"
                    class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                <option value="">すべて</option>
                <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>公開</option>
                <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>非公開</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-sm hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                絞り込む
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
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">ID</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">タイトル</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">種別</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">公開状態</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">公開日</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">操作</th>
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
                                @if($information->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 border border-success-500 bg-success-50 text-xs font-medium text-success-700">公開中</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 border border-secondary-300 bg-secondary-50 text-xs font-medium text-secondary-600">非公開</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">
                                {{ $information->published_at ? $information->published_at->format('Y/n/j') : '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.information.edit', $information) }}" class="text-sm text-secondary-700 hover:text-secondary-900 mr-4">編集</a>
                                <form action="{{ route('admin.information.destroy', $information) }}" method="POST" class="inline"
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
            {{ $informations->links() }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <p class="text-secondary-500">お知らせが見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
