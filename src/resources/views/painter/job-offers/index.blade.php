@extends('layouts.app')

@section('title', '送った個別依頼')

@section('content')

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">送った個別依頼</span>
        </div>
        <p class="page-header-subtitle">Sent Offers</p>
        <h1 class="page-header-title mt-2">送った個別依頼</h1>
        <p class="text-secondary-500 text-sm mt-3">モデルへ送った個別依頼の状況一覧です。</p>
    </div>
</div>

<div class="page space-y-6">

    @if($offers->isEmpty())
        <div class="border border-dashed border-secondary-300 px-5 py-12 text-center">
            <p class="text-sm text-secondary-500">まだ個別依頼を送っていません。</p>
            <p class="text-xs text-secondary-400 mt-1">モデル詳細ページから「個別で仕事を依頼する」を押して送信できます。</p>
        </div>
    @else
        <ul class="space-y-3">
            @foreach($offers as $offer)
                @php
                    $model = $offer->model;
                    $modelName = $model->modelProfile?->display_name ?? $model->name;
                @endphp
                <li class="bg-canvas-50 border border-secondary-200 rounded-lg p-4 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="font-semibold text-secondary-900 truncate">{{ $offer->job->title }}</p>
                        <p class="text-xs text-secondary-500 mt-1">{{ $modelName }} さん宛・{{ $offer->created_at->format('Y/m/d') }}</p>
                        @if($offer->isAccepted() || $offer->isDeclined())
                            <p class="text-xs text-secondary-400 mt-1">返答: {{ $offer->responded_at?->format('Y/m/d') }}</p>
                        @endif
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full shrink-0
                                 @if($offer->isAccepted()) bg-success-50 text-success-700 border border-success-200
                                 @elseif($offer->isDeclined()) bg-secondary-100 text-secondary-600 border border-secondary-200
                                 @else bg-warning-50 text-warning-700 border border-warning-200
                                 @endif">
                        @if($offer->isAccepted()) 受諾
                        @elseif($offer->isDeclined()) 辞退
                        @else 返答待ち
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>

        <div class="flex justify-center">
            {{ $offers->links() }}
        </div>
    @endif
</div>

@endsection
