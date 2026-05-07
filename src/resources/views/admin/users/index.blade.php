@extends('admin.layouts.app')

@section('content')

{{-- ヘッダー --}}
<div class="border-b border-secondary-200 pb-5 mb-8">
    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Users</p>
    <h1 class="font-display text-2xl font-semibold text-secondary-900">ユーザー管理</h1>
</div>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.users.index') }}"
      class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="keyword" class="block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Keyword</label>
            <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}"
                   placeholder="名前・メールアドレス"
                   class="w-full px-4 py-2.5 bg-canvas-50 border border-secondary-300 text-secondary-900 text-sm focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900 transition-colors duration-200">
        </div>
        <div>
            <label for="role" class="block text-[10px] tracking-[0.25em] uppercase text-secondary-500 mb-2">Role</label>
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
                    class="w-full px-6 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Search
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
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Name</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Email</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Role</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Created</th>
                        <th class="px-5 py-3 text-left text-[10px] font-medium text-secondary-500 uppercase tracking-[0.2em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                    @foreach($users as $user)
                        @php
                            $roleInfo = match($user->role) {
                                'admin'   => ['label' => '管理者', 'class' => 'text-error-600'],
                                'painter' => ['label' => '画家',   'class' => 'text-secondary-900'],
                                default   => ['label' => 'モデル', 'class' => 'text-secondary-700'],
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
                                <span class="text-[10px] tracking-[0.25em] uppercase {{ $roleInfo['class'] }}">
                                    ● {{ $roleInfo['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                {{ $user->created_at->format('Y . n . j') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-700 hover:text-secondary-900 mr-4">Detail</a>
                                @if($user->role !== 'admin')
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] tracking-[0.25em] uppercase text-error-600 hover:text-error-700">Delete</button>
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
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-3">No Users</p>
            <p class="text-secondary-500 text-sm">ユーザーが見つかりませんでした。</p>
        </div>
    @endif
</div>
@endsection
