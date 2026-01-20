@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">ユーザー詳細</h1>
    <a href="{{ route('admin.users.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- 基本情報 --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">基本情報</h2>
        </div>
        <div class="card-body">
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-secondary-500">ID</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $user->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">名前</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">メールアドレス</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">ロール</dt>
                    <dd class="mt-1">
                        <span class="badge badge-{{ $user->role === 'admin' ? 'error' : ($user->role === 'painter' ? 'primary' : 'accent') }}">
                            {{ $user->role === 'admin' ? '管理者' : ($user->role === 'painter' ? '画家' : 'モデル') }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-secondary-500">登録日</dt>
                    <dd class="mt-1 text-sm text-secondary-900">{{ $user->created_at->format('Y年m月d日 H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- プロフィール情報 --}}
    <div class="card">
        <div class="card-header">
            <h2 class="text-lg font-semibold text-secondary-900">プロフィール情報</h2>
        </div>
        <div class="card-body">
            @if($user->role === 'model' && $user->modelProfile)
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-secondary-500">表示名</dt>
                        <dd class="mt-1 text-sm text-secondary-900">{{ $user->modelProfile->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-secondary-500">都道府県</dt>
                        <dd class="mt-1 text-sm text-secondary-900">{{ $user->modelProfile->prefecture ?? '未設定' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-secondary-500">公開状態</dt>
                        <dd class="mt-1">
                            <span class="badge badge-{{ $user->modelProfile->is_public ? 'success' : 'secondary' }}">
                                {{ $user->modelProfile->is_public ? '公開' : '非公開' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            @elseif($user->role === 'painter' && $user->painterProfile)
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-secondary-500">表示名</dt>
                        <dd class="mt-1 text-sm text-secondary-900">{{ $user->painterProfile->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-secondary-500">都道府県</dt>
                        <dd class="mt-1 text-sm text-secondary-900">{{ $user->painterProfile->prefecture ?? '未設定' }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-secondary-500 text-sm">プロフィール情報がありません</p>
            @endif
        </div>
    </div>
</div>

{{-- アクティビティ --}}
<div class="card mt-6">
    <div class="card-header">
        <h2 class="text-lg font-semibold text-secondary-900">アクティビティ</h2>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <dt class="text-sm font-medium text-secondary-500">投稿した依頼数</dt>
                <dd class="mt-1 text-2xl font-bold text-secondary-900">{{ $user->jobs->count() }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">応募数</dt>
                <dd class="mt-1 text-2xl font-bold text-secondary-900">{{ $user->jobApplications->count() }}</dd>
            </div>
        </div>
    </div>
</div>

@if($user->role !== 'admin')
    <div class="mt-6">
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
              onsubmit="return confirm('本当にこのユーザーを削除しますか？この操作は取り消せません。');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-secondary bg-error-600 hover:bg-error-700 text-white">
                ユーザーを削除
            </button>
        </form>
    </div>
@endif
@endsection

