@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">ユーザー管理</h1>
</div>

{{-- 検索フォーム --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="keyword" class="form-label">キーワード</label>
                    <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" 
                           placeholder="名前・メールアドレス" class="form-input">
                </div>
                <div>
                    <label for="role" class="form-label">ロール</label>
                    <select id="role" name="role" class="form-input">
                        <option value="">すべて</option>
                        <option value="model" {{ request('role') === 'model' ? 'selected' : '' }}>モデル</option>
                        <option value="painter" {{ request('role') === 'painter' ? 'selected' : '' }}>画家</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>管理者</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-primary w-full">検索</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ユーザー一覧 --}}
<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-secondary-200">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">名前</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">メール</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">ロール</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">登録日</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">操作</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-secondary-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-secondary-900">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-700">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-{{ $user->role === 'admin' ? 'error' : ($user->role === 'painter' ? 'primary' : 'accent') }}">
                                        {{ $user->role === 'admin' ? '管理者' : ($user->role === 'painter' ? '画家' : 'モデル') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-500">
                                    {{ $user->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-700 mr-3">詳細</a>
                                    @if($user->role !== 'admin')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                              onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error-600 hover:text-error-700">削除</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @else
            <p class="text-secondary-500 text-center py-8">ユーザーが見つかりませんでした</p>
        @endif
    </div>
</div>
@endsection

