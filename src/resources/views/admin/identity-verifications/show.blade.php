@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.identity-verifications.index') }}" class="text-sm text-primary-600 hover:underline">← 一覧に戻る</a>
</div>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-secondary-900">本人確認 詳細審査</h1>
    <p class="text-secondary-500 mt-2">提出された書類を確認し、承認または差し戻しを行います。</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- 左：書類画像 --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="card-body">
                <h2 class="font-bold text-lg text-secondary-900 mb-4">提出書類</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-semibold text-secondary-700 mb-2">表面</p>
                        <img src="{{ route('admin.identity-verifications.image', [$verification, 'front']) }}"
                             alt="表面" class="max-w-full rounded-lg border border-secondary-200">
                    </div>

                    @if($verification->back_image_path)
                        <div>
                            <p class="text-sm font-semibold text-secondary-700 mb-2">裏面</p>
                            <img src="{{ route('admin.identity-verifications.image', [$verification, 'back']) }}"
                                 alt="裏面" class="max-w-full rounded-lg border border-secondary-200">
                        </div>
                    @endif

                    @if($verification->selfie_image_path)
                        <div>
                            <p class="text-sm font-semibold text-secondary-700 mb-2">セルフィー</p>
                            <img src="{{ route('admin.identity-verifications.image', [$verification, 'selfie']) }}"
                                 alt="セルフィー" class="max-w-full rounded-lg border border-secondary-200">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 右：申請情報・操作 --}}
    <div class="space-y-6">
        <div class="card">
            <div class="card-body">
                <h2 class="font-bold text-lg text-secondary-900 mb-4">申請情報</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-secondary-500">ユーザー</dt>
                        <dd class="font-semibold text-secondary-900">{{ $verification->user->name }}</dd>
                        <dd class="text-xs text-secondary-500">{{ $verification->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-secondary-500">書類種別</dt>
                        <dd class="font-medium">{{ $verification->document_type_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-secondary-500">提出日時</dt>
                        <dd>{{ $verification->created_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-secondary-500">現在のステータス</dt>
                        <dd>
                            @php
                                $statusClass = match($verification->status) {
                                    'approved' => 'bg-success-100 text-success-700',
                                    'rejected' => 'bg-error-100 text-error-700',
                                    'reviewing' => 'bg-primary-100 text-primary-700',
                                    default => 'bg-warning-100 text-warning-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $statusClass }}">
                                {{ $verification->status_label }}
                            </span>
                        </dd>
                    </div>
                    @if($verification->reviewed_at)
                        <div>
                            <dt class="text-secondary-500">確認者</dt>
                            <dd>{{ $verification->reviewer?->name ?? '不明' }}（{{ $verification->reviewed_at->format('Y/m/d H:i') }}）</dd>
                        </div>
                    @endif
                    @if($verification->rejection_reason)
                        <div>
                            <dt class="text-secondary-500">差し戻し理由</dt>
                            <dd class="text-error-700 bg-error-50 p-2 rounded text-xs">{{ $verification->rejection_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        @if(in_array($verification->status, ['pending', 'reviewing']))
            <div class="card">
                <div class="card-body">
                    <h2 class="font-bold text-lg text-secondary-900 mb-4">審査操作</h2>

                    <form method="POST" action="{{ route('admin.identity-verifications.approve', $verification) }}" class="mb-4">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('この申請を承認しますか？')"
                                class="btn-primary w-full">
                            ✓ 承認する
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.identity-verifications.reject', $verification) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="form-label">差し戻し理由</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-textarea"
                                      placeholder="例: 書類の四隅が写っていません"></textarea>
                            @error('rejection_reason')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit"
                                onclick="return confirm('この申請を差し戻しますか？')"
                                class="btn-danger w-full">
                            ✗ 差し戻す
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
