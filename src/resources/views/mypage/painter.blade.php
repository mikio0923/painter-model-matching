@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">My Page</p>
        <h1 class="page-header-title mt-2">マイページ</h1>
        @if($painterProfile)
            <p class="text-secondary-500 text-sm mt-3">{{ $painterProfile->display_name }} さんの制作拠点です。</p>
        @else
            <p class="text-secondary-500 text-sm mt-3">プロフィールを整え、依頼を作成しましょう。</p>
        @endif
    </div>
</div>

<div class="page space-y-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ========== 左カラム: プロフィール + 統計 ========== --}}
        <aside class="space-y-8 lg:col-span-1">

            {{-- プロフィールカード --}}
            @if($painterProfile)
                <div class="bg-canvas-50 border border-secondary-200">
                    @if($painterProfile->profile_image_path)
                        <div class="aspect-[3/4] bg-secondary-100 overflow-hidden">
                            <img src="{{ Storage::url($painterProfile->profile_image_path) }}" alt="{{ $painterProfile->display_name }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-5">
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Profile</p>
                        <h3 class="font-display text-xl font-semibold text-secondary-900 mb-2">{{ $painterProfile->display_name }}</h3>
                        @if($painterProfile->prefecture)
                            <p class="text-xs text-secondary-500 mb-4">{{ $painterProfile->prefecture }}</p>
                        @endif

                        @if($painterProfile->art_styles && count($painterProfile->art_styles) > 0)
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($painterProfile->art_styles as $style)
                                    <span class="text-[10px] tracking-wider uppercase text-secondary-600 border border-secondary-300 px-2 py-0.5">{{ $style }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if($painterProfile->portfolio_url)
                            <p class="text-xs mb-4 pt-4 border-t border-secondary-200">
                                <a href="{{ $painterProfile->portfolio_url }}" target="_blank" rel="noopener" class="link-primary inline-flex items-center gap-1">
                                    External Portfolio
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </p>
                        @endif

                        <a href="{{ route('painter.profile.edit') }}" class="block w-full text-center py-2.5 border border-secondary-900 text-secondary-900 text-xs uppercase tracking-[0.2em] hover:bg-secondary-900 hover:text-canvas-50 transition-colors duration-300">
                            Edit Profile
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-canvas-50 border border-secondary-300 p-6">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">Profile</p>
                    <h3 class="font-display text-lg font-semibold text-secondary-900 mb-3">未作成</h3>
                    <p class="text-sm text-secondary-600 mb-5 leading-relaxed">
                        画家として活動するために、プロフィールを作成してください。依頼の作成にはプロフィール登録が必要です。
                    </p>
                    <a href="{{ route('painter.profile.edit') }}" class="block w-full text-center py-2.5 bg-secondary-900 text-canvas-50 text-xs uppercase tracking-[0.2em] hover:bg-canvas-50 hover:text-secondary-900 border border-secondary-900 transition-colors duration-300">
                        プロフィールを作成
                    </a>
                </div>
            @endif

            {{-- 制作統計（クリックで詳細へ） --}}
            <div class="border border-secondary-200">
                <div class="px-5 py-3 border-b border-secondary-200">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Activity</p>
                </div>
                <div class="grid grid-cols-2 divide-x divide-y divide-secondary-200">
                    <a href="{{ route('painter.jobs.index') }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $totalJobs }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">総依頼</div>
                    </a>
                    <a href="{{ route('painter.jobs.index', ['status' => 'open']) }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $openJobs }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">公開中</div>
                    </a>
                    <a href="{{ route('painter.jobs.index', ['status' => 'done']) }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $completedJobs }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">完了</div>
                    </a>
                    <a href="{{ route('painter.applications.index', ['filter' => 'pending']) }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold {{ ($pendingApplications ?? 0) > 0 ? 'text-error-600' : 'text-secondary-900' }} tabular-nums transition-colors">{{ $pendingApplications ?? 0 }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">未対応の応募</div>
                    </a>
                    <a href="{{ route('painter.applications.index', ['filter' => 'accepted']) }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $acceptedApplications }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">承認</div>
                    </a>
                    <a href="{{ route('favorites.index') }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $totalFavorites }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">お気に入り</div>
                    </a>
                </div>
            </div>
        </aside>

        {{-- ========== 中央 & 右カラム: メニュー + 依頼一覧 ========== --}}
        <div class="lg:col-span-2 space-y-12">

            {{-- 警告（プロフィール未作成） --}}
            @if(!$painterProfile)
                <div class="bg-canvas-50 border-l-2 border-warning-500 px-5 py-4">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-warning-700 mb-2">Notice</p>
                    <p class="text-sm text-secondary-700">
                        <a href="{{ route('painter.profile.edit') }}" class="link-primary">画家プロフィールの登録</a>がまだ完了していません。依頼の作成にはプロフィール登録が必要です。
                    </p>
                </div>
            @endif

            {{-- メニュー --}}
            <section>
                <div class="flex items-baseline justify-between mb-6">
                    <div>
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Menu</p>
                        <h2 class="font-display text-2xl font-semibold text-secondary-900">アクション</h2>
                    </div>
                    <a href="{{ route('painter.jobs.create') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-secondary-900 text-canvas-50 text-[10px] uppercase tracking-[0.25em] border border-secondary-900 hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                        + New Job
                    </a>
                </div>

                @php
                    $menuItems = [
                        [
                            'href' => route('painter.jobs.create'),
                            'label' => '新しい依頼を作成',
                            'sub' => 'Create Job',
                            'desc' => 'モデルへの新規依頼を作成する',
                        ],
                        [
                            'href' => route('painter.jobs.index'),
                            'label' => '自分の依頼',
                            'sub' => 'My Jobs',
                            'desc' => '投稿した依頼の管理・編集',
                        ],
                        [
                            'href' => route('painter.applications.index'),
                            'label' => '受け取った応募',
                            'sub' => 'Applications',
                            'desc' => 'モデルからの応募を採用・辞退する',
                            'badge' => $pendingApplications ?? 0,
                        ],
                        [
                            'href' => route('painter.job-offers.index'),
                            'label' => '送った個別依頼',
                            'sub' => 'Sent Offers',
                            'desc' => 'モデルへの個別依頼の状況',
                        ],
                        [
                            'href' => route('models.index'),
                            'label' => 'モデルを探す',
                            'sub' => 'Browse Models',
                            'desc' => '登録モデルから直接探す',
                        ],
                        [
                            'href' => route('messages.index'),
                            'label' => 'メッセージ',
                            'sub' => 'Messages',
                            'desc' => 'モデルとのやり取り',
                            'badge' => $unreadMessages ?? 0,
                        ],
                        [
                            'href' => route('painter.profile.edit'),
                            'label' => 'プロフィール編集',
                            'sub' => 'Edit Profile',
                            'desc' => '表示名・スタイル・ポートフォリオURL',
                        ],
                        [
                            'href' => route('favorites.index'),
                            'label' => 'お気に入り',
                            'sub' => 'Favorites',
                            'desc' => 'ブックマークしたモデル・依頼',
                        ],
                        [
                            'href' => route('mypage') . '#account-settings',
                            'label' => 'アカウント設定',
                            'sub' => 'Account',
                            'desc' => 'メール・パスワード・退会',
                        ],
                        [
                            'href' => route('account.email-preferences.edit'),
                            'label' => 'メール配信設定',
                            'sub' => 'Email',
                            'desc' => '受信したいメールの種類を選択',
                        ],
                        [
                            'href' => route('guide.painter'),
                            'label' => '画家ガイド',
                            'sub' => 'Guide',
                            'desc' => '依頼作成・モデルとのやり取り方法',
                        ],
                        [
                            'href' => route('guideline'),
                            'label' => '利用ガイドライン',
                            'sub' => 'Guidelines',
                            'desc' => 'サービス利用上の注意',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 border-t-2 border-l-2 border-secondary-400">
                    @foreach($menuItems as $item)
                        <a href="{{ $item['href'] }}"
                           class="group block px-5 py-5 border-r-2 border-b-2 border-secondary-400 hover:bg-secondary-200 hover:border-secondary-700 transition-colors duration-200 relative">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] tracking-[0.3em] uppercase text-secondary-400 mb-1">{{ $item['sub'] }}</p>
                                    <p class="font-display text-base font-semibold text-secondary-900 mb-1 flex items-center gap-2">
                                        {{ $item['label'] }}
                                        @if(!empty($item['badge']) && $item['badge'] > 0)
                                            <span class="inline-flex items-center justify-center min-w-[18px] h-4 px-1 bg-secondary-900 text-canvas-50 text-[9px] font-medium">{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-secondary-500 leading-relaxed">{{ $item['desc'] }}</p>
                                </div>
                                <svg class="w-4 h-4 text-secondary-300 group-hover:text-secondary-900 transition-colors duration-300 flex-shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- 最近の依頼 --}}
            <section>
                <div class="flex items-baseline justify-between mb-6">
                    <div>
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Recent</p>
                        <h2 class="font-display text-2xl font-semibold text-secondary-900">最近の依頼</h2>
                    </div>
                    @if($jobs->isNotEmpty())
                        <a href="{{ route('painter.jobs.index') }}" class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                            View All →
                        </a>
                    @endif
                </div>

                @if($jobs->isEmpty())
                    <div class="border border-secondary-200 px-5 py-12 text-center">
                        <p class="text-sm text-secondary-500 mb-5">まだ依頼がありません。</p>
                        <a href="{{ route('painter.jobs.create') }}" class="inline-block px-5 py-2 border border-secondary-900 text-secondary-900 text-[10px] uppercase tracking-[0.25em] hover:bg-secondary-900 hover:text-canvas-50 transition-colors duration-300">
                            最初の依頼を作成
                        </a>
                    </div>
                @else
                    <div class="border-t border-secondary-200">
                        @foreach($jobs as $job)
                            @php
                                $statusLabel = match($job->status) {
                                    'open' => ['公開中', 'text-success-700'],
                                    'closed' => ['締切', 'text-secondary-500'],
                                    'done' => ['完了', 'text-secondary-700'],
                                    default => ['—', 'text-secondary-400'],
                                };
                            @endphp
                            <div class="flex items-baseline justify-between gap-4 px-1 py-4 border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="text-[10px] tracking-[0.2em] uppercase {{ $statusLabel[1] }}">
                                            ● {{ $statusLabel[0] }}
                                        </span>
                                        <span class="text-[10px] tracking-[0.2em] uppercase text-secondary-400">
                                            {{ $job->created_at->format('Y . n . j') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('jobs.show', $job) }}" class="text-sm text-secondary-900 hover:text-secondary-700 transition-colors line-clamp-1 block">
                                        {{ $job->title }}
                                    </a>
                                    <p class="text-xs text-secondary-500 mt-1">
                                        応募 <span class="text-secondary-700 font-medium">{{ $job->applications->count() }}</span> 件
                                    </p>
                                </div>
                                <a href="{{ route('painter.jobs.applications.index', $job) }}" class="text-[10px] tracking-[0.25em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors whitespace-nowrap">
                                    Applicants →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>

    {{-- ========== アカウント設定（基本情報・パスワード・退会） ========== --}}
    <div class="pt-4">
        @include('mypage.partials.account-settings')
    </div>
</div>
@endsection
