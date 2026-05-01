@extends('admin.layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-secondary-900">本人確認 審査管理</h1>
    <p class="text-secondary-500 mt-2">モデルから提出された本人確認書類の審査を行います。</p>
</div>

{{-- ステータスタブ --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach(['pending' => '審査待ち', 'reviewing' => '審査中', 'approved' => '承認済み', 'rejected' => '差し戻し', 'all' => 'すべて'] as $key => $label)
        <a href="{{ route('admin.identity-verifications.index', ['status' => $key]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ $currentStatus === $key ? 'bg-primary-600 text-white' : 'bg-white text-secondary-600 border border-secondary-200 hover:bg-secondary-50' }}">
            {{ $label }}
            @if($key !== 'all' && isset($counts[$key]))
                <span class="ml-1 text-xs">({{ $counts[$key] }})</span>
            @endif
        </a>
    @endforeach
</div>

@if($verifications->count() === 0)
    <div class="card">
        <div class="card-body text-center py-12 text-secondary-500">
            該当する申請はありません。
        </div>
    </div>
@else
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-secondary-700">ユーザー</th>
                        <th class="text-left px-4 py-3 font-semibold text-secondary-700">書類種別</th>
                        <th class="text-left px-4 py-3 font-semibold text-secondary-700">ステータス</th>
                        <th class="text-left px-4 py-3 font-semibold text-secondary-700">提出日時</th>
                        <th class="text-left px-4 py-3 font-semibold text-secondary-700">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-100">
                    @foreach($verifications as $v)
                        <tr class="hover:bg-secondary-50">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-secondary-900">{{ $v->user->name }}</div>
                                <div class="text-xs text-secondary-500">{{ $v->user->email }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $v->document_type_label }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass = match($v->status) {
                                        'approved' => 'bg-success-100 text-success-700',
                                        'rejected' => 'bg-error-100 text-error-700',
                                        'reviewing' => 'bg-primary-100 text-primary-700',
                                        default => 'bg-warning-100 text-warning-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $statusClass }}">
                                    {{ $v->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-secondary-600">{{ $v->created_at->format('Y/m/d H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.identity-verifications.show', $v) }}" class="text-primary-600 hover:underline text-sm font-medium">
                                    詳細・審査
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $verifications->links() }}
    </div>
@endif
@endsection
