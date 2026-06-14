@extends('layouts.app')

@section('content')

{{-- ページヘッダー（背景に名画が静かに循環） --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">Job Listings</p>
        <h1 class="page-header-title mt-2">依頼一覧</h1>
        <p class="text-secondary-500 text-sm mt-3">画家からの撮影・モデリング依頼を一覧でご覧いただけます。</p>
    </div>
</div>

<div class="page">

    {{-- 検索フォーム --}}
    <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-6 mb-8">
        <form method="GET" action="{{ route('jobs.index') }}" id="job-search-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- キーワード --}}
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="keyword" class="form-label">キーワード</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="keyword" name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="タイトル・説明で検索"
                               class="form-input pl-10">
                    </div>
                </div>

                {{-- 都道府県 --}}
                <div>
                    <label for="prefecture" class="form-label">都道府県</label>
                    <select id="prefecture" name="prefecture" class="form-input">
                        <option value="">すべて</option>
                        @foreach($prefectures as $pref)
                            <option value="{{ $pref }}" {{ request('prefecture') === $pref ? 'selected' : '' }}>{{ $pref }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 場所タイプ --}}
                <div>
                    <label for="location_type" class="form-label">場所タイプ</label>
                    <select id="location_type" name="location_type" class="form-input">
                        <option value="">すべて</option>
                        <option value="online"  {{ request('location_type') === 'online'  ? 'selected' : '' }}>オンライン</option>
                        <option value="offline" {{ request('location_type') === 'offline' ? 'selected' : '' }}>オフライン</option>
                    </select>
                </div>

                {{-- 報酬範囲 --}}
                <div class="sm:col-span-2 lg:col-span-2">
                    <label class="form-label">報酬（円）</label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="reward_min" name="reward_min"
                               value="{{ request('reward_min') }}"
                               placeholder="最小" min="0"
                               class="form-input">
                        <span class="text-secondary-400 text-sm shrink-0">〜</span>
                        <input type="number" id="reward_max" name="reward_max"
                               value="{{ request('reward_max') }}"
                               placeholder="最大" min="0"
                               class="form-input">
                    </div>
                </div>

                {{-- ボタン --}}
                <div class="sm:col-span-2 lg:col-span-2 flex items-end gap-3 sm:justify-end">
                    <button type="submit" class="btn-primary flex-1 sm:flex-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        検索
                    </button>
                    <button type="button" onclick="resetSearchForm()" class="btn-secondary flex-1 sm:flex-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        リセット
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- 検索結果 --}}
    @if(request()->hasAny(['keyword', 'prefecture', 'location_type', 'reward_min', 'reward_max']))
        <p class="text-sm text-secondary-500 mb-5">
            <span class="font-semibold text-secondary-700">{{ $jobs->total() }}</span> 件の依頼が見つかりました
        </p>
    @endif

    @if($jobs->count() === 0)
        <div class="bg-canvas-50 rounded-xl border border-secondary-200 p-16 text-center">
            <div class="w-16 h-16 rounded-full bg-secondary-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-secondary-500 font-medium">該当する依頼が見つかりませんでした</p>
            <p class="text-sm text-secondary-400 mt-1">検索条件を変えてお試しください</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($jobs as $job)
            @php
                $painter       = $job->painter;
                $painterProfile = $painter->painterProfile;
                $painterName   = $painterProfile?->display_name ?? $painter->name;
                $painterImage  = $painterProfile?->profile_image_path ?? null;
                $isFavJob      = in_array($job->id, $favoriteJobIds ?? []);
            @endphp
            <div class="job-card group relative">
                <a href="{{ route('jobs.show', $job) }}" class="block job-card-body">
                    {{-- 画家情報 --}}
                    <div class="flex items-center gap-2.5 mb-3 pr-9">
                        <div class="avatar avatar-sm border border-primary-100">
                            @if($painterImage)
                                <img src="{{ Storage::url($painterImage) }}" alt="{{ $painterName }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-3.5 h-3.5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <p class="text-xs text-secondary-500 truncate flex-1">{{ $painterName }}</p>
                    </div>

                    {{-- タイトル・説明 --}}
                    <h2 class="text-sm font-bold text-secondary-900 line-clamp-2 mb-2 leading-snug">{{ $job->title }}</h2>
                    <p class="text-xs text-secondary-500 line-clamp-2 mb-3 leading-relaxed">
                        {{ mb_strlen($job->description) > 80 ? mb_substr($job->description, 0, 80) . '…' : $job->description }}
                    </p>

                    {{-- メタ情報 --}}
                    <div class="space-y-1.5 pt-3 border-t border-secondary-100 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-secondary-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                場所
                            </span>
                            <span class="text-secondary-700 font-medium">
                                {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                                @if($job->prefecture)（{{ $job->prefecture }}）@endif
                            </span>
                        </div>
                        @if($job->reward_amount)
                        <div class="flex items-center justify-between">
                            <span class="text-secondary-400">報酬</span>
                            <span class="text-secondary-700 font-medium">
                                ¥{{ number_format($job->reward_amount) }}<span class="text-secondary-400 font-normal">{{ $job->reward_unit === 'per_hour' ? '/時間' : '/回' }}</span>
                            </span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-secondary-400">日程</span>
                            <span class="text-secondary-600">{{ $job->scheduled_date ? $job->scheduled_date->format('Y/m/d') : '未定' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-3 text-[11px] text-secondary-400">
                        <span>{{ $job->created_at->format('Y/m/d') }}</span>
                        @if($job->applications_count > 0)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                応募 {{ $job->applications_count }}件
                            </span>
                        @endif
                    </div>
                </a>

                <div class="absolute top-3 right-3 z-10">
                    <x-favorite-button type="job" :id="$job->id" :favorited="$isFavJob" />
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 flex justify-center">
            {{ $jobs->links() }}
        </div>
    @endif

</div>

<script>
function resetSearchForm() {
    ['keyword','prefecture','location_type','reward_min','reward_max'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
}
</script>
@endsection
