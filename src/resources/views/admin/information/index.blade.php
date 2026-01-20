@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">お知らせ管理</h1>
    <a href="{{ route('admin.information.create') }}" class="btn-primary">新規作成</a>
</div>

{{-- フィルタ --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.information.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="type" class="form-label">タイプ</label>
                    <select id="type" name="type" class="form-input">
                        <option value="">すべて</option>
                        <option value="information" {{ request('type') === 'information' ? 'selected' : '' }}>お知らせ</option>
                        <option value="press_release" {{ request('type') === 'press_release' ? 'selected' : '' }}>プレスリリース</option>
                    </select>
                </div>
                <div>
                    <label for="is_published" class="form-label">公開状態</label>
                    <select id="is_published" name="is_published" class="form-input">
                        <option value="">すべて</option>
                        <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>公開</option>
                        <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>非公開</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">フィルタ</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- お知らせ一覧 --}}
<div class="card">
    <div class="card-body">
        @if($informations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">タイトル</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">タイプ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">公開状態</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">公開日</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @foreach($informations as $information)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">{{ $information->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">
                                    <a href="{{ route('admin.information.edit', $information) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ $information->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">
                                    {{ $information->type === 'information' ? 'お知らせ' : 'プレスリリース' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-{{ $information->is_published ? 'success' : 'secondary' }}">
                                        {{ $information->is_published ? '公開' : '非公開' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">
                                    {{ $information->published_at ? $information->published_at->format('Y/m/d') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.information.edit', $information) }}" class="text-primary-600 hover:text-primary-700 mr-3">編集</a>
                                    <form action="{{ route('admin.information.destroy', $information) }}" method="POST" class="inline" 
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
                {{ $informations->links() }}
            </div>
        @else
            <p class="text-secondary-500 text-center py-8">お知らせが見つかりませんでした</p>
        @endif
    </div>
</div>
@endsection

