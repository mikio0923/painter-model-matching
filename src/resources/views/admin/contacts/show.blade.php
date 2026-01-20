@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">お問い合わせ詳細</h1>
    <div class="flex gap-2">
        @if(!$contact->is_read)
            <form action="{{ route('admin.contacts.read', $contact) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-secondary">既読にする</button>
            </form>
        @endif
        <a href="{{ route('admin.contacts.index') }}" class="btn-secondary">一覧に戻る</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-secondary-500">ID</dt>
                <dd class="mt-1 text-sm text-secondary-900">{{ $contact->id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">件名</dt>
                <dd class="mt-1 text-sm text-secondary-900">{{ $contact->subject }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">名前</dt>
                <dd class="mt-1 text-sm text-secondary-900">{{ $contact->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">メールアドレス</dt>
                <dd class="mt-1 text-sm text-secondary-900">{{ $contact->email }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">状態</dt>
                <dd class="mt-1">
                    @if($contact->is_read)
                        <span class="badge badge-secondary">既読</span>
                    @else
                        <span class="badge badge-error">未読</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">受信日</dt>
                <dd class="mt-1 text-sm text-secondary-900">{{ $contact->created_at->format('Y年m月d日 H:i') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-secondary-500">内容</dt>
                <dd class="mt-1 text-sm text-secondary-900 whitespace-pre-wrap">{{ $contact->message }}</dd>
            </div>
        </dl>
    </div>
</div>

<div class="mt-6">
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" 
          onsubmit="return confirm('本当にこのお問い合わせを削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-secondary bg-error-600 hover:bg-error-700 text-white">
            お問い合わせを削除
        </button>
    </form>
</div>
@endsection

