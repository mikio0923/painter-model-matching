@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">お問い合わせ管理</h1>
</div>

{{-- フィルタ --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="space-y-4">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="flex items-center">
                        <input type="checkbox" name="unread" value="1" {{ request('unread') ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-secondary-700">未読のみ表示</span>
                    </label>
                </div>
                <button type="submit" class="btn-primary">フィルタ</button>
            </div>
        </form>
    </div>
</div>

{{-- お問い合わせ一覧 --}}
<div class="card">
    <div class="card-body">
        @if($contacts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">件名</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">名前</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">メール</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">状態</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">受信日</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @foreach($contacts as $contact)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">{{ $contact->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ $contact->subject }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $contact->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $contact->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($contact->is_read)
                                        <span class="badge badge-secondary">既読</span>
                                    @else
                                        <span class="badge badge-error">未読</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">
                                    {{ $contact->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="text-primary-600 hover:text-primary-700 mr-3">詳細</a>
                                    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline" 
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
                {{ $contacts->links() }}
            </div>
        @else
            <p class="text-secondary-500 text-center py-8">お問い合わせが見つかりませんでした</p>
        @endif
    </div>
</div>
@endsection

