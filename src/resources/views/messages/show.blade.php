@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('messages.index') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Messages
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current max-w-xs">{{ $otherUser->name ?? '退会済みユーザー' }}</span>
        </div>
        <p class="page-header-subtitle">Conversation</p>
        <h1 class="page-header-title mt-2">{{ $job->title }}</h1>
        <p class="page-header-meta mt-2">
            相手: <span class="text-secondary-700">{{ $otherUser->name ?? '退会済みユーザー' }}</span>
        </p>
    </div>
</div>

@php
    $lastId = $messages->isNotEmpty() ? $messages->last()->id : 0;
@endphp

<div class="page-narrow space-y-6">

    {{-- メッセージ一覧 --}}
    <div id="messages-scroll" class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6 max-h-[600px] overflow-y-auto">
        <div id="messages-empty" class="text-center py-12 {{ $messages->isEmpty() ? '' : 'hidden' }}">
            <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-400 mb-2">No Messages</p>
            <p class="text-secondary-500 text-sm">最初のメッセージを送ってください。</p>
        </div>
        <div id="messages-list" class="space-y-5 {{ $messages->isEmpty() ? 'hidden' : '' }}">
            @foreach($messages as $message)
                @php $isMe = $message->sender_id === Auth::id(); @endphp
                <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                    <div class="max-w-[85%] sm:max-w-md">
                        <div class="flex items-center gap-2 mb-1.5 {{ $isMe ? 'justify-end' : '' }}">
                            <span class="text-xs font-medium text-secondary-700">
                                {{ $message->sender->name ?? '退会済みユーザー' }}
                            </span>
                            <span class="text-[10px] tracking-[0.15em] uppercase text-secondary-400">
                                {{ $message->created_at->format('m/d H:i') }}
                            </span>
                        </div>
                        <div class="px-4 py-3 text-sm whitespace-pre-wrap leading-relaxed {{ $isMe ? 'bg-secondary-900 text-canvas-50 border border-secondary-900' : 'bg-canvas-50 text-secondary-800 border border-secondary-300' }}">{{ $message->body }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- メッセージ送信フォーム --}}
    <div class="border border-secondary-200 bg-canvas-50 p-5 sm:p-6">
        <form id="message-form" action="{{ route('messages.store', $job) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $otherUser->id ?? '' }}">

            <div>
                <label for="body" class="block text-[10px] uppercase tracking-[0.25em] text-secondary-500 mb-2">
                    Send Message
                </label>
                <textarea id="body" name="body" rows="4" required
                          placeholder="メッセージを入力してください（Cmd/Ctrl + Enter で送信）"
                          class="form-textarea"></textarea>
                <p id="form-error" class="form-error hidden"></p>
                @error('body')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" id="message-send-btn"
                        class="px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Send
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function(){
    const POLL_INTERVAL_MS = 4000;
    const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const storeUrl    = {!! json_encode(route('messages.store', $job)) !!};
    const pollUrl     = {!! json_encode(route('messages.poll', $job)) !!};
    const otherUserId = {!! json_encode($otherUser->id ?? null) !!};

    const scrollEl    = document.getElementById('messages-scroll');
    const listEl      = document.getElementById('messages-list');
    const emptyEl     = document.getElementById('messages-empty');
    const form        = document.getElementById('message-form');
    const textarea    = document.getElementById('body');
    const submitBtn   = document.getElementById('message-send-btn');
    const errorEl     = document.getElementById('form-error');

    if (!scrollEl || !listEl || !form || !textarea || !submitBtn || !otherUserId) return;

    let lastId = {{ $lastId }};
    let pollTimer = null;
    let isPolling = false;

    // 既存メッセージから最大 id を再計算（@foreach の最後 = last_id）
    listEl.querySelectorAll('[data-message-id]').forEach(el => {
        const id = parseInt(el.dataset.messageId, 10);
        if (!isNaN(id) && id > lastId) lastId = id;
    });

    function scrollToBottom() {
        scrollEl.scrollTop = scrollEl.scrollHeight;
    }

    function appendMessage(msg) {
        if (document.querySelector('[data-message-id="' + msg.id + '"]')) return;

        const wrap = document.createElement('div');
        wrap.className = 'flex ' + (msg.is_me ? 'justify-end' : 'justify-start');
        wrap.dataset.messageId = msg.id;

        const inner = document.createElement('div');
        inner.className = 'max-w-[85%] sm:max-w-md';

        const header = document.createElement('div');
        header.className = 'flex items-center gap-2 mb-1.5 ' + (msg.is_me ? 'justify-end' : '');

        const nameEl = document.createElement('span');
        nameEl.className = 'text-xs font-medium text-secondary-700';
        nameEl.textContent = msg.sender_name;

        const timeEl = document.createElement('span');
        timeEl.className = 'text-[10px] tracking-[0.15em] uppercase text-secondary-400';
        timeEl.textContent = msg.created_at;

        header.appendChild(nameEl);
        header.appendChild(timeEl);

        const bubble = document.createElement('div');
        bubble.className = 'px-4 py-3 text-sm whitespace-pre-wrap leading-relaxed '
            + (msg.is_me
                ? 'bg-secondary-900 text-canvas-50 border border-secondary-900'
                : 'bg-canvas-50 text-secondary-800 border border-secondary-300');
        bubble.textContent = msg.body; // textContent で XSS 回避

        inner.appendChild(header);
        inner.appendChild(bubble);
        wrap.appendChild(inner);
        listEl.appendChild(wrap);

        if (msg.id > lastId) lastId = msg.id;

        // 空メッセージ状態を解除
        emptyEl?.classList.add('hidden');
        listEl.classList.remove('hidden');
    }

    async function pollOnce() {
        if (isPolling) return;
        if (document.hidden) return; // タブが裏なら停止
        isPolling = true;
        try {
            const url = new URL(pollUrl, window.location.origin);
            url.searchParams.set('with', otherUserId);
            url.searchParams.set('since', lastId);

            const res = await fetch(url.toString(), {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!res.ok) return;
            const data = await res.json();
            const newest = (data.messages || []);
            if (newest.length) {
                const wasAtBottom = (scrollEl.scrollTop + scrollEl.clientHeight) >= (scrollEl.scrollHeight - 16);
                newest.forEach(appendMessage);
                if (wasAtBottom) scrollToBottom();
            }
        } catch (e) {
            // ネットワーク一時失敗はサイレントに（次の interval で再試行）
        } finally {
            isPolling = false;
        }
    }

    function startPolling() {
        if (pollTimer) return;
        pollTimer = setInterval(pollOnce, POLL_INTERVAL_MS);
    }
    function stopPolling() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    async function sendMessage() {
        const body = textarea.value.trim();
        errorEl?.classList.add('hidden');
        if (!body) {
            errorEl.textContent = 'メッセージを入力してください。';
            errorEl.classList.remove('hidden');
            return;
        }
        submitBtn.disabled = true;
        try {
            const fd = new FormData();
            fd.append('body', body);
            fd.append('receiver_id', otherUserId);

            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
                body: fd,
            });

            if (res.status === 422) {
                const data = await res.json().catch(() => ({}));
                const firstMsg = data?.errors
                    ? Object.values(data.errors).flat()[0]
                    : (data?.message || '送信に失敗しました。');
                errorEl.textContent = firstMsg;
                errorEl.classList.remove('hidden');
                return;
            }
            if (!res.ok) {
                errorEl.textContent = '送信に失敗しました。時間を置いて再度お試しください。';
                errorEl.classList.remove('hidden');
                return;
            }

            const data = await res.json();
            if (data?.message) {
                appendMessage(data.message);
                scrollToBottom();
            }
            textarea.value = '';
            textarea.focus();
        } catch (e) {
            errorEl.textContent = '通信エラーが発生しました。';
            errorEl.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
        }
    }

    form.addEventListener('submit', function(e){
        e.preventDefault();
        sendMessage();
    });

    // Cmd/Ctrl + Enter で送信
    textarea.addEventListener('keydown', function(e){
        if ((e.metaKey || e.ctrlKey) && e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
    });

    document.addEventListener('visibilitychange', function(){
        if (document.hidden) {
            stopPolling();
        } else {
            startPolling();
            pollOnce(); // 戻ってきたら即チェック
        }
    });

    scrollToBottom();
    startPolling();
})();
</script>
@endsection
