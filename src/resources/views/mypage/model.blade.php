@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    {{-- ヘッダー --}}
    <h1 class="text-2xl font-bold mb-2">
        <span class="text-gray-400">&gt;</span> MYPAGE <span class="text-gray-400 text-base font-normal">/ マイページ</span>
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Row 1: メニュー | 警告+プロフィール --}}
        <div class="flex flex-col">
            {{-- メインメニュー（3x3グリッド） --}}
            <div class="grid grid-cols-3 gap-0">
                {{-- モデルのお仕事 --}}
                <a href="{{ route('jobs.index') }}" class="bg-gray-800 hover:bg-gray-700 text-white p-3 flex flex-col items-center justify-center text-center border-r border-b border-gray-700 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="text-xs">モデルのお仕事</span>
                </a>
                {{-- メッセージBOX --}}
                <a href="{{ route('messages.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white p-3 flex flex-col items-center justify-center text-center border-r border-b border-gray-600 transition-colors relative">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="text-xs">メッセージBOX</span>
                    @if($unreadMessages > 0)<span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadMessages }}</span>@endif
                </a>
                {{-- プロフィール --}}
                <a href="{{ route('model.profile.edit') }}" class="bg-gray-800 hover:bg-gray-700 text-white p-3 flex flex-col items-center justify-center text-center border-b border-gray-700 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span class="text-xs">プロフィール</span>
                </a>
                {{-- モデルになるガイド --}}
                <a href="{{ route('guide.model') }}" class="bg-gray-700 hover:bg-gray-600 text-white p-3 flex flex-col items-center justify-center text-center border-r border-b border-gray-600 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-xs">モデルになるガイド</span>
                </a>
                {{-- あなたへの質問 --}}
                <a href="{{ route('model.questions.index') }}" class="bg-gray-800 hover:bg-gray-700 text-white p-3 flex flex-col items-center justify-center text-center border-r border-b border-gray-700 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs">あなたへの質問</span>
                </a>
                {{-- PHOTO（ポートフォリオ） --}}
                <a href="{{ route('model.profile.edit') }}#photos" class="bg-gray-700 hover:bg-gray-600 text-white p-3 flex flex-col items-center justify-center text-center border-b border-gray-600 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs">PHOTO<br>(ポートフォリオ)</span>
                </a>
                {{-- ご利用ガイドライン --}}
                <a href="{{ route('guideline') }}" class="bg-gray-800 hover:bg-gray-700 text-white p-3 flex flex-col items-center justify-center text-center border-r border-gray-700 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    <span class="text-xs">ご利用ガイドライン</span>
                </a>
                {{-- エントリー履歴 --}}
                <a href="{{ route('model.applications.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white p-3 flex flex-col items-center justify-center text-center border-r border-gray-600 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span class="text-xs">エントリー履歴</span>
                </a>
                {{-- 他のメニュー（折りたたみトリガー） --}}
                <button type="button" onclick="toggleOtherMenu()" class="bg-gray-800 hover:bg-gray-700 text-white p-3 flex flex-col items-center justify-center text-center transition-colors" id="otherMenuBtn">
                    <svg class="w-6 h-6 mb-1 transition-transform duration-300" id="otherMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    <span class="text-xs">他のメニュー</span>
                </button>
            </div>
            {{-- 折りたたみメニュー --}}
            <div id="otherMenu" class="grid grid-cols-3 gap-0 transition-all duration-300 max-h-0 overflow-hidden opacity-0 pointer-events-none" aria-hidden="true" data-closed="true">
                <a href="{{ route('model.identity-verification') }}" class="bg-gray-600 hover:bg-gray-500 text-white p-3 flex flex-col items-center justify-center text-center border-r border-b border-gray-500 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs">本人確認</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="bg-gray-700 hover:bg-gray-600 text-white p-3 flex flex-col items-center justify-center text-center border-b border-gray-600 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="text-xs">アカウント<br>(登録情報)</span>
                </a>
                <a href="{{ route('model.paid-options') }}" class="bg-gray-600 hover:bg-gray-500 text-white p-3 flex flex-col items-center justify-center text-center border-b border-gray-500 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span class="text-xs">有料オプション</span>
                </a>
                <a href="{{ route('model.billing-history') }}" class="bg-gray-700 hover:bg-gray-600 text-white p-3 flex flex-col items-center justify-center text-center border-r border-gray-600 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="text-xs">課金・決済履歴</span>
                </a>
                <a href="{{ route('model.payment-method') }}" class="bg-gray-600 hover:bg-gray-500 text-white p-3 flex flex-col items-center justify-center text-center border-r border-gray-500 transition-colors">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="text-xs">カード情報登録</span>
                </a>
                <div class="bg-gray-700 p-3"></div>
            </div>
        </div>

        {{-- 右側 Row 1: 警告+プロフィール（メニューと高さ揃え） --}}
        <div class="flex flex-col space-y-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-700 mb-2">
                    本人確認の設定は<a href="{{ route('model.identity-verification') }}" class="text-blue-600 hover:underline">こちら</a>から。オファー率を高めるためにご利用ください。
                </p>
                <a href="{{ route('model.profile.edit') }}" class="block text-sm text-red-500 hover:text-red-600 hover:underline transition-colors">モデルプロフィールの登録がまだのようです。ジョブへエントリーするのに必要となります。</a>
            </div>
            @if($modelProfile)
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-auto">
                    <div class="flex items-start gap-4 mb-4">
                        @if($modelProfile->profile_image_path)
                            <img src="{{ Storage::url($modelProfile->profile_image_path) }}" alt="{{ $modelProfile->display_name }}" class="w-16 h-16 object-cover rounded-full">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold">{{ $modelProfile->display_name }}</h3>
                            <div class="text-xs text-gray-500">@if($modelProfile->prefecture){{ $modelProfile->prefecture }}@endif @if($modelProfile->age) {{ $modelProfile->age }}歳@endif</div>
                            <div class="text-xs mt-1">
                                <span class="inline-block px-2 py-0.5 rounded {{ $modelProfile->is_public ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $modelProfile->is_public ? '公開中' : '非公開' }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('model.profile.edit') }}" class="block w-full text-center bg-blue-600 text-white text-sm py-2 rounded hover:bg-blue-700 transition-colors">プロフィールを編集</a>
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-auto">
                    <p class="text-sm text-gray-600 mb-4">プロフィールを作成すると、画家からの依頼を受けられます。</p>
                    <a href="{{ route('model.profile.edit') }}" class="block w-full text-center bg-blue-600 text-white text-sm py-2 rounded hover:bg-blue-700 transition-colors">プロフィールを作成する</a>
                </div>
            @endif
        </div>

        {{-- Row 2 左: 広告 --}}
        <div>
            <div class="bg-pink-500 text-white p-4 rounded-t-lg lg:mt-0">
                <h3 class="font-bold">おすすめのお仕事</h3>
            </div>
            <div class="bg-white border border-t-0 border-gray-200 p-4 rounded-b-lg mb-6">
                <p class="text-pink-500 text-sm mb-4">モデルに興味がある女性なら必見！ 登録済みならごめんなさい！</p>
                
                @if($recentJobs->isEmpty())
                    <p class="text-gray-500 text-sm">現在おすすめのお仕事はありません</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentJobs as $index => $job)
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-pink-100 text-pink-500 rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                                <a href="{{ route('jobs.show', $job) }}" class="text-sm text-gray-700 hover:text-pink-500 transition-colors">
                                    {{ Str::limit($job->title, 50) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- サイトからのお知らせ --}}
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-4">
                    <span class="text-gray-400">&gt;</span> ModelTownからモデルの皆さまへ
                </h2>
                @if($siteNotices->isEmpty())
                    <p class="text-gray-500 text-sm">お知らせはありません</p>
                @else
                    <div class="space-y-2">
                        @foreach($siteNotices as $notice)
                            <div class="flex items-center gap-4 py-2 border-b border-gray-200">
                                <span class="text-sm text-gray-500">{{ $notice->created_at->format('Y.n.j') }}</span>
                                <a href="{{ route('information.show', $notice) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ $notice->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Row 2 右: お知らせ・統計 --}}
        <div class="space-y-6">
            {{-- お知らせセクション --}}
            <div>
                <h2 class="text-xl font-bold mb-4">
                    <span class="text-gray-400">&gt;</span> INFORMATION <span class="text-gray-400 text-sm font-normal">/ お知らせ</span>
                </h2>
                @if($information->isEmpty())
                    <p class="text-gray-500 text-sm">お知らせはありません</p>
                @else
                    <div class="space-y-3">
                        @foreach($information as $info)
                            <div class="flex items-start gap-3">
                                <span class="text-sm text-gray-500 whitespace-nowrap">{{ $info->created_at->format('Y.n.j') }}</span>
                                <a href="{{ route('information.show', $info) }}" class="text-sm text-gray-700 hover:text-blue-600">
                                    {{ $info->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-right mt-4">
                        <a href="{{ route('information.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            すべて表示
                        </a>
                    </div>
                @endif
            </div>

            {{-- 統計情報 --}}
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold mb-3">あなたの活動状況</h3>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="bg-blue-50 rounded p-3">
                        <div class="text-xl font-bold text-blue-600">{{ $totalApplications }}</div>
                        <div class="text-xs text-gray-600">総応募数</div>
                    </div>
                    <div class="bg-green-50 rounded p-3">
                        <div class="text-xl font-bold text-green-600">{{ $acceptedApplications }}</div>
                        <div class="text-xs text-gray-600">承認済み</div>
                    </div>
                    <div class="bg-purple-50 rounded p-3">
                        <div class="text-xl font-bold text-purple-600">{{ $completedJobs }}</div>
                        <div class="text-xs text-gray-600">完了した依頼</div>
                    </div>
                    <div class="bg-pink-50 rounded p-3">
                        <div class="text-xl font-bold text-pink-600">{{ $totalFavorites }}</div>
                        <div class="text-xs text-gray-600">お気に入り数</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleOtherMenu() {
    const menu = document.getElementById('otherMenu');
    const icon = document.getElementById('otherMenuIcon');
    const closed = menu.getAttribute('data-closed') === 'true';
    
    if (closed) {
        menu.classList.remove('max-h-0', 'opacity-0', 'pointer-events-none');
        menu.classList.add('max-h-[500px]', 'opacity-100', 'pointer-events-auto');
        menu.setAttribute('data-closed', 'false');
        menu.setAttribute('aria-hidden', 'false');
        icon.classList.add('rotate-180');
    } else {
        menu.classList.add('max-h-0', 'opacity-0', 'pointer-events-none');
        menu.classList.remove('max-h-[500px]', 'opacity-100', 'pointer-events-auto');
        menu.setAttribute('data-closed', 'true');
        menu.setAttribute('aria-hidden', 'true');
        icon.classList.remove('rotate-180');
    }
}
</script>
@endsection
