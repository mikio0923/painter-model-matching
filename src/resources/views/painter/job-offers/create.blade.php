@extends('layouts.app')

@section('title', '個別で仕事を依頼する')

@section('content')

<div class="page-header">
    <x-art-bg-stage />
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('models.show', $modelProfile) }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ $modelProfile->display_name ?? $modelProfile->user->name }} さん
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">個別で仕事を依頼する</span>
        </div>
        <p class="page-header-subtitle">Personal Offer</p>
        <h1 class="page-header-title mt-2">個別で仕事を依頼する</h1>
        <p class="text-secondary-500 text-sm mt-3">
            {{ $modelProfile->display_name ?? $modelProfile->user->name }} さんに対して、既存の依頼または新規依頼を個別に送信できます。
        </p>
    </div>
</div>

<div class="page space-y-8">

    @if(session('error'))
        <div class="border-l-2 border-error-500 bg-canvas-50 px-4 py-3 text-sm text-secondary-700">
            <p class="text-xs text-error-700 mb-1 font-medium">エラー</p>
            {{ session('error') }}
        </div>
    @endif

    {{-- 新規依頼を作成して送るルート --}}
    <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
        <h2 class="font-display text-lg font-semibold text-secondary-900 mb-2">新しい依頼を作成して送る</h2>
        <p class="text-sm text-secondary-500 mb-4">
            このモデルさん向けに依頼内容を新規作成します。作成画面では指名先が自動で設定されます。
        </p>
        <a href="{{ route('painter.jobs.create', ['model_id' => $modelProfile->id, 'as_offer' => 1]) }}"
           class="btn-museum-dark">
            新しく依頼を作って送る
        </a>
    </section>

    {{-- 既存の open な依頼から選ぶルート --}}
    <section class="bg-canvas-50 border border-secondary-200 rounded-xl p-6">
        <h2 class="font-display text-lg font-semibold text-secondary-900 mb-2">既存の依頼から選んで送る</h2>
        <p class="text-sm text-secondary-500 mb-5">
            あなたが作成済みで、現在公開中かつ他のモデルへ個別依頼中ではない依頼から選択できます。
            送信中はその依頼は一時的に非公開となります。
        </p>

        @if($availableJobs->isEmpty())
            <div class="border border-dashed border-secondary-300 px-5 py-10 text-center">
                <p class="text-sm text-secondary-500">送信できる依頼がありません。</p>
                <p class="text-xs text-secondary-400 mt-1">公開中で他のモデルへ個別依頼中でない依頼が必要です。</p>
            </div>
        @else
            <form action="{{ route('painter.job-offers.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="model_id" value="{{ $modelProfile->user_id }}">

                <div class="space-y-3">
                    @foreach($availableJobs as $job)
                        <label class="block border border-secondary-200 hover:border-secondary-900 p-4 cursor-pointer transition-colors {{ $loop->first ? 'has-[:checked]:border-secondary-900' : '' }}">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="job_id" value="{{ $job->id }}"
                                       {{ $loop->first ? 'checked' : '' }}
                                       class="mt-1 accent-secondary-900">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-secondary-900 line-clamp-1">{{ $job->title }}</p>
                                    <p class="text-xs text-secondary-500 line-clamp-2 mt-1">{{ $job->description }}</p>
                                    <div class="flex flex-wrap gap-3 mt-2 text-xs text-secondary-500">
                                        @if($job->reward_amount)
                                            <span>¥{{ number_format($job->reward_amount) }}</span>
                                        @endif
                                        @if($job->scheduled_date)
                                            <span>{{ $job->scheduled_date->format('Y/m/d') }}</span>
                                        @endif
                                        <span>{{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div>
                    <label for="painter_message" class="block text-sm font-medium text-secondary-800 mb-2">
                        メッセージ（任意・最大 2000 文字）
                    </label>
                    <textarea name="painter_message" id="painter_message" rows="5"
                              maxlength="2000"
                              class="w-full px-3 py-2 bg-white border border-secondary-300 text-secondary-900 text-sm leading-relaxed
                                     focus:outline-none focus:border-secondary-900 focus:ring-1 focus:ring-secondary-900"
                              placeholder="ご挨拶や依頼の意図、希望する撮影内容など">{{ old('painter_message') }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-museum-dark">
                        この依頼を送信する
                    </button>
                    <a href="{{ route('models.show', $modelProfile) }}"
                       class="text-sm text-secondary-500 hover:text-secondary-900 transition-colors">
                        キャンセル
                    </a>
                </div>
            </form>
        @endif
    </section>
</div>

@endsection
