@extends('admin.layouts.app')

@section('content')

{{-- ヘッダー --}}
<div class="border-b border-secondary-200 pb-5 mb-8">
    <h1 class="font-display text-2xl font-semibold text-secondary-900">ユーザー管理</h1>
    <p class="text-sm text-secondary-500 mt-1">登録ユーザーの検索・確認・削除ができます。</p>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.users.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="keyword" class="block text-sm font-medium text-secondary-700 mb-1.5">キーワード</label>
            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
                   placeholder="名前・メールアドレス"
                   class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-secondary-700 mb-1.5">ロール</label>
            <select id="role" name="role"
                    class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900">
                <option value="">すべて</option>
                <option value="model"   {{ request('role') === 'model'   ? 'selected' : '' }}>モデル</option>
                <option value="painter" {{ request('role') === 'painter' ? 'selected' : '' }}>画家</option>
                <option value="admin"   {{ request('role') === 'admin'   ? 'selected' : '' }}>管理者</option>
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

{{-- ユーザー一覧 --}}
<div class="border border-secondary-200 bg-canvas-50">
    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">ID</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">名前</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">メールアドレス</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">ロール</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">登録日</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-secondary-600">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                    @foreach($users as $user)
                        @php
                            $roleInfo = match($user->role) {
                                'admin'   => ['label' => '管理者', 'border' => 'border-error-500',     'bg' => 'bg-error-50',     'text' => 'text-error-700'],
                                'painter' => ['label' => '画家',   'border' => 'border-primary-500',   'bg' => 'bg-primary-50',   'text' => 'text-primary-700'],
                                default   => ['label' => 'モデル', 'border' => 'border-success-500',   'bg' => 'bg-success-50',   'text' => 'text-success-700'],
                            };
                        @endphp
                        <tr class="hover:bg-secondary-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $user->id }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-secondary-900 hover:text-secondary-700 transition-colors">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-600">{{ $user->email }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 border {{ $roleInfo['border'] }} {{ $roleInfo['bg'] }} text-xs font-medium {{ $roleInfo['text'] }}">
                                    {{ $roleInfo['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-secondary-500">
                                {{ $user->created_at->format('Y/n/j') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-secondary-700 hover:text-secondary-900 mr-4">詳細</a>
                                @if($user->role !== 'admin')
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-error-600 hover:text-error-700">削除</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-secondary-200">
            {{ $users->links() }}
        </div>
    @else
        <div class="px-5 py-16 text-center">
            <p class="text-secondary-500">ユーザーが見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
