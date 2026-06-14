@extends('layouts.app')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="art-bg-stage">
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/pearl-earring.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/great-wave.jpg') }}');"></div>
        <div class="art-bg-layer" style="background-image: url('{{ asset('images/art-bg/starry-night.jpg') }}');"></div>
        <div class="art-bg-veil"></div>
    </div>
    <div class="page-header-inner">
        <p class="page-header-subtitle">My Page</p>
        <h1 class="page-header-title mt-2">マイページ</h1>
        @if($modelProfile)
            <p class="text-secondary-500 text-sm mt-3">{{ $modelProfile->display_name }} さんの活動拠点です。</p>
        @else
            <p class="text-secondary-500 text-sm mt-3">アカウント情報を整え、活動を開始しましょう。</p>
        @endif
    </div>
</div>

<div class="page space-y-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ========== 左カラム: プロフィール + 統計 ========== --}}
        <aside class="space-y-8 lg:col-span-1">

            {{-- プロフィールカード --}}
            @if($modelProfile)
                <div class="bg-canvas-50 border border-secondary-200">
                    <div class="aspect-[3/4] bg-secondary-100 overflow-hidden">
                        @if($modelProfile->profile_image_path)
                            <img src="{{ Storage::url($modelProfile->profile_image_path) }}" alt="{{ $modelProfile->display_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-secondary-300" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Profile</p>
                        <h3 class="font-display text-xl font-semibold text-secondary-900 mb-2">{{ $modelProfile->display_name }}</h3>
                        <p class="text-xs text-secondary-500 mb-4">
                            @if($modelProfile->prefecture){{ $modelProfile->prefecture }}@endif
                            @if($modelProfile->age) ・ {{ $modelProfile->age }}歳@endif
                        </p>
                        <div class="flex items-center justify-between mb-3 pt-4 border-t border-secondary-200">
                            <span class="text-[10px] uppercase tracking-[0.2em] text-secondary-500">Status</span>
                            @if($modelProfile->is_public)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border-2 border-success-500 bg-success-50 text-success-700 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
                                    公開中
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border-2 border-secondary-300 bg-secondary-50 text-secondary-600 text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary-400"></span>
                                    非公開
                                </span>
                            @endif
                        </div>
                        {{-- 本人確認バッジ（一旦停止）
                        @if($modelProfile->identity_verified)
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] uppercase tracking-[0.2em] text-secondary-500">Identity</span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border-2 border-success-500 bg-success-50 text-success-700 text-xs font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    確認済み
                                </span>
                            </div>
                        @endif
                        --}}
                        <a href="{{ route('model.profile.edit') }}" class="block w-full text-center py-2.5 border border-secondary-900 text-secondary-900 text-xs uppercase tracking-[0.2em] hover:bg-secondary-900 hover:text-canvas-50 transition-colors duration-300">
                            Edit Profile
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-canvas-50 border border-secondary-300 p-6">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-2">Profile</p>
                    <h3 class="font-display text-lg font-semibold text-secondary-900 mb-3">未作成</h3>
                    <p class="text-sm text-secondary-600 mb-5 leading-relaxed">
                        プロフィールを作成すると、画家からの依頼を受けられるようになります。
                    </p>
                    <a href="{{ route('model.profile.edit') }}" class="block w-full text-center py-2.5 bg-secondary-900 text-canvas-50 text-xs uppercase tracking-[0.2em] hover:bg-canvas-50 hover:text-secondary-900 border border-secondary-900 transition-colors duration-300">
                        プロフィールを作成
                    </a>
                </div>
            @endif

            {{-- 活動統計（クリックで詳細へ） --}}
            <div class="border border-secondary-200">
                <div class="px-5 py-3 border-b border-secondary-200">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Activity</p>
                </div>
                <div class="grid grid-cols-2 divide-x divide-y divide-secondary-200">
                    <a href="{{ route('model.applications.index') }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $totalApplications }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">総応募</div>
                    </a>
                    <a href="{{ route('model.applications.index') }}?status=accepted" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $acceptedApplications }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">承認</div>
                    </a>
                    <a href="{{ route('model.applications.index') }}?status=completed" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                        <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $completedJobs }}</div>
                        <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">完了</div>
                    </a>
                    @if($modelProfile)
                        <a href="{{ route('models.show', $modelProfile) }}" class="p-5 text-center hover:bg-secondary-50 transition-colors group">
                            <div class="text-3xl font-semibold text-secondary-900 tabular-nums group-hover:text-secondary-700 transition-colors">{{ $totalFavorites }}</div>
                            <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">お気に入り</div>
                        </a>
                    @else
                        <div class="p-5 text-center">
                            <div class="text-3xl font-semibold text-secondary-900 tabular-nums">{{ $totalFavorites }}</div>
                            <div class="text-[10px] uppercase tracking-[0.2em] text-secondary-500 mt-1">お気に入り</div>
                        </div>
                    @endif
                </div>
            </div>
        </aside>

        {{-- ========== 中央 & 右カラム: メニュー + お知らせ ========== --}}
        <div class="lg:col-span-2 space-y-12">

            {{-- 警告（プロフィール未作成） --}}
            @if(!$modelProfile)
                <div class="bg-canvas-50 border-l-2 border-warning-500 px-5 py-4">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-warning-700 mb-2">Notice</p>
                    <p class="text-sm text-secondary-700">
                        <a href="{{ route('model.profile.edit') }}" class="link-primary">モデルプロフィールの登録</a>がまだ完了していません。依頼への応募にはプロフィール登録が必要です。
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
                </div>

                @php
                    $menuItems = [
                        [
                            'href' => route('jobs.index'),
                            'label' => '依頼を探す',
                            'sub' => 'Browse Jobs',
                            'desc' => '画家からの依頼を一覧から探す',
                        ],
                        [
                            'href' => route('messages.index'),
                            'label' => 'メッセージ',
                            'sub' => 'Messages',
                            'desc' => '画家とのやり取りを確認する',
                            'badge' => $unreadMessages ?? 0,
                        ],
                        [
                            'href' => route('model.applications.index'),
                            'label' => 'エントリー履歴',
                            'sub' => 'Applications',
                            'desc' => '応募状況・承認状況を確認',
                        ],
                        [
                            'href' => route('model.profile.edit'),
                            'label' => 'プロフィール編集',
                            'sub' => 'Edit Profile',
                            'desc' => '表示名・身体情報・経歴を編集',
                        ],
                        [
                            'href' => route('model.profile.edit') . '#photos',
                            'label' => 'ポートフォリオ',
                            'sub' => 'Portfolio',
                            'desc' => '掲載写真の追加・並び替え',
                        ],
                        [
                            'href' => route('model.questions.index'),
                            'label' => 'あなたへの質問',
                            'sub' => 'Q&A',
                            'desc' => '画家からの質問に回答する',
                        ],
                        // 本人確認は一旦停止
                        // [
                        //     'href' => route('model.identity-verification'),
                        //     'label' => '本人確認',
                        //     'sub' => 'Identity',
                        //     'desc' => '書類提出・確認状況の管理',
                        // ],
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
                            'href' => route('guide.model'),
                            'label' => 'モデルガイド',
                            'sub' => 'Guide',
                            'desc' => 'はじめての方に',
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

            {{-- おすすめのお仕事 --}}
            @if($recentJobs->isNotEmpty())
            <section>
                <div class="flex items-baseline justify-between mb-6">
                    <div>
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Recommended</p>
                        <h2 class="font-display text-2xl font-semibold text-secondary-900">おすすめの依頼</h2>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                        View All →
                    </a>
                </div>

                <div class="border-t border-secondary-200">
                    @foreach($recentJobs as $job)
                        <a href="{{ route('jobs.show', $job) }}"
                           class="group flex items-baseline justify-between gap-4 px-1 py-4 border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300">
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] tracking-[0.2em] uppercase text-secondary-400 mb-1">
                                    {{ $job->created_at->format('Y . n . j') }}
                                </p>
                                <p class="text-sm text-secondary-900 group-hover:text-secondary-700 transition-colors line-clamp-1">
                                    {{ $job->title }}
                                </p>
                            </div>
                            <svg class="w-3 h-3 text-secondary-300 group-hover:text-secondary-900 transition-colors duration-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- お知らせ --}}
            @if($information->isNotEmpty() || $siteNotices->isNotEmpty())
            <section>
                <div class="flex items-baseline justify-between mb-6">
                    <div>
                        <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 mb-1">Information</p>
                        <h2 class="font-display text-2xl font-semibold text-secondary-900">お知らせ</h2>
                    </div>
                    @if($information->isNotEmpty())
                        <a href="{{ route('information.index') }}" class="text-[10px] tracking-[0.3em] uppercase text-secondary-500 hover:text-secondary-900 transition-colors">
                            View All →
                        </a>
                    @endif
                </div>

                @php
                    $allNotices = $siteNotices->merge($information)->sortByDesc('created_at')->take(5);
                @endphp

                @if($allNotices->isEmpty())
                    <p class="text-sm text-secondary-500">現在お知らせはありません。</p>
                @else
                    <div class="border-t border-secondary-200">
                        @foreach($allNotices as $notice)
                            <a href="{{ route('information.show', $notice) }}"
                               class="group flex items-baseline justify-between gap-4 px-1 py-4 border-b border-secondary-200 hover:bg-secondary-50 transition-colors duration-300">
                                <div class="min-w-0 flex-1 flex items-baseline gap-4">
                                    <span class="text-[10px] tracking-[0.2em] uppercase text-secondary-400 whitespace-nowrap">
                                        {{ $notice->created_at->format('Y . n . j') }}
                                    </span>
                                    <span class="text-sm text-secondary-900 group-hover:text-secondary-700 transition-colors line-clamp-1">
                                        {{ $notice->title }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>
            @endif

        </div>
    </div>

    {{-- ========== アカウント設定（基本情報・パスワード・退会） ========== --}}
    <div class="pt-4">
        @include('mypage.partials.account-settings')
    </div>
</div>
@endsection
