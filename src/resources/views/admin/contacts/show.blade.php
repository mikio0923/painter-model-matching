@extends('admin.layouts.app')

@section('content')

<div class="border-b border-secondary-200 pb-5 mb-8 flex items-end justify-between gap-4 flex-wrap">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.contacts.index') }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Contacts
            </a>
            <span class="text-secondary-300">/</span>
            <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-700">Detail</span>
        </div>
        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Contact</p>
        <h1 class="font-display text-2xl font-semibold text-secondary-900">{{ $contact->subject }}</h1>
    </div>
    @if(!$contact->is_read)
        <form action="{{ route('admin.contacts.read', $contact) }}" method="POST">
            @csrf
            <button type="submit"
                    class="px-5 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.25em] hover:bg-secondary-100 transition-colors duration-200">
                Mark as Read
            </button>
        </form>
    @endif
</div>

<div class="border border-secondary-200 bg-canvas-50 mb-6">
    <dl class="divide-y divide-secondary-200">
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">ID</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->id }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">Subject</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->subject }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">Name</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->name }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">Email</dt>
            <dd class="text-sm text-secondary-900 break-all">{{ $contact->email }}</dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">Status</dt>
            <dd>
                @if($contact->is_read)
                    <span class="text-[10px] tracking-[0.25em] uppercase text-secondary-500">○ 既読</span>
                @else
                    <span class="text-[10px] tracking-[0.25em] uppercase text-error-600">● 未読</span>
                @endif
            </dd>
        </div>
        <div class="px-5 py-3 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">Received</dt>
            <dd class="text-sm text-secondary-900">{{ $contact->created_at->format('Y年n月j日 H:i') }}</dd>
        </div>
        <div class="px-5 py-4 flex gap-4">
            <dt class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 w-28 shrink-0 pt-0.5">Message</dt>
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
                class="px-6 py-2.5 border border-error-500 text-error-600 text-xs uppercase tracking-[0.25em] hover:bg-error-50 transition-colors duration-200">
            Delete Contact
        </button>
    </form>
</div>
@endsection
