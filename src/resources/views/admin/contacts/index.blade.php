@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8">
    <h1 class="font-display text-2xl font-semibold text-secondary-900">お問い合わせ管理</h1>
    <p class="text-sm text-secondary-500 mt-1">利用者から寄せられたお問い合わせを確認できます。</p>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.contacts.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="keyword" class="block text-sm font-medium text-secondary-700 mb-1.5">キーワード</label>
            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
                   placeholder="名前・メール・件名・内容"
                   class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
        </div>
        <div>
            <p class="block text-sm font-medium text-secondary-700 mb-1.5">絞り込み</p>
            <label class="flex items-center h-10 gap-2 cursor-pointer">
                <input type="checkbox" name="unread" value="1" {{ request('unread') ? 'checked' : '' }}
                       class="border-secondary-300 text-secondary-900 focus:ring-secondary-900">
                <span class="text-sm text-secondary-700">未読のみ表示</span>
            </label>
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
    @if($contacts->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">ID</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">件名</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">お名前</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">メールアドレス</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">状態</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">受信日</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                    @foreach($contacts as $contact)
                        <tr class="hover:bg-secondary-50 transition-colors {{ !$contact->is_read ? 'bg-warning-50/30' : '' }}">
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $contact->id }}</td>
                            <td class="px-5 py-4 text-sm">
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="text-secondary-900 hover:text-secondary-700 transition-colors {{ !$contact->is_read ? 'font-medium' : '' }}">
                                    {{ $contact->subject }}
                                </a>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-600">{{ $contact->name }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-600 break-all">{{ $contact->email }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($contact->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 border border-secondary-300 bg-secondary-50 text-xs font-medium text-secondary-600">既読</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 border border-error-500 bg-error-50 text-xs font-medium text-error-700">未読</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">
                                {{ $contact->created_at->format('Y/n/j') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.contacts.show', $contact) }}" class="text-sm text-secondary-700 hover:text-secondary-900 mr-4">詳細</a>
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline"
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
            {{ $contacts->links() }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <p class="text-secondary-500">お問い合わせが見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
