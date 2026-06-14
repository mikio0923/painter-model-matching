@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8 flex items-end justify-between gap-4 flex-wrap">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.contacts.index') }}" class="text-sm text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                お問い合わせ一覧
            </a>
            <span class="text-secondary-300">/</span>
            <span class="text-sm text-secondary-700">詳細</span>
        </div>
        <h1 class="font-display text-2xl font-semibold text-secondary-900">{{ $contact->subject }}</h1>
        <p class="text-sm text-secondary-500 mt-1">お問い合わせの詳細内容</p>
    </div>
    @if(!$contact->is_read)
        <form action="{{ route('admin.contacts.read', $contact) }}" method="POST">
            @csrf
            <button type="submit"
                    class="px-5 py-2.5 border border-secondary-400 text-secondary-700 text-sm hover:bg-secondary-100 transition-colors duration-200">
                既読にする
            </button>
        </form>
    @endif
</div>

<div class="border border-secondary-200 bg-canvas-50 mb-6">
    <dl class="divide-y divide-secondary-200">
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">ID</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->id }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">件名</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->subject }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">お名前</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->name }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">メールアドレス</dt>
            <dd class="text-sm text-secondary-900 break-all">{{ $contact->email }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4 items-center">
            <dt class="text-sm text-secondary-500 w-28 shrink-0">状態</dt>
            <dd>
                @if($contact->is_read)
                    <span class="inline-flex items-center px-2.5 py-0.5 border border-secondary-300 bg-secondary-50 text-xs font-medium text-secondary-600">既読</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 border border-error-500 bg-error-50 text-xs font-medium text-error-700">未読</span>
                @endif
            </dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">受信日</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->created_at->format('Y年n月j日 H:i') }}</dd>
        </div>
        <div class="px-5 py-4 flex gap-4">
            <dt class="text-sm text-secondary-500 w-28 shrink-0 pt-0.5">本文</dt>
            <dd class="text-sm text-secondary-900 whitespace-pre-wrap leading-relaxed">{{ $contact->message }}</dd>
        </div>
    </dl>
</div>

<div class="flex justify-end">
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
          onsubmit="return confirm('本当にこのお問い合わせを削除しますか？');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="px-6 py-2.5 border border-error-500 text-error-600 text-sm hover:bg-error-50 transition-colors duration-200">
            お問い合わせを削除
        </button>
    </form>
</div>
@endsection
