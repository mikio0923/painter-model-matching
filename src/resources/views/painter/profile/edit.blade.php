@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')

{{-- ページヘッダー --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="flex items-center gap-2 mb-3">
            <a href="{{ route('mypage') }}" class="page-header-breadcrumb">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                My Page
            </a>
            <span class="page-header-breadcrumb-sep">/</span>
            <span class="page-header-breadcrumb-current">プロフィール編集</span>
        </div>
        <p class="page-header-subtitle">Edit Profile</p>
        <h1 class="page-header-title mt-2">画家プロフィール</h1>
        <p class="text-secondary-500 text-sm mt-3">活動拠点・作風・経歴などをモデルに伝える情報を編集します。</p>
    </div>
</div>

<div class="page-narrow">
    @if(session('success'))
        <div class="border-l-2 border-success-500 bg-canvas-50 px-4 py-3 mb-6 text-sm text-secondary-700">
            <p class="text-[10px] uppercase tracking-[0.3em] text-success-700 mb-1">Saved</p>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('painter.profile.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- ── 基本情報 ── --}}
        <section class="border border-secondary-200 bg-canvas-50">
            <div class="px-5 py-3 border-b border-secondary-200">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Basic</p>
            </div>
            <div class="p-5 sm:p-6 space-y-5">
                <div>
                    <label for="display_name" class="form-label">
                        表示名 <span class="text-error-500">*</span>
                    </label>
                    <input type="text" id="display_name" name="display_name" required
                           value="{{ old('display_name', $painterProfile->display_name) }}"
                           class="form-input">
                    @error('display_name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="prefecture" class="form-label">活動拠点（都道府県）</label>
                    <input type="text" id="prefecture" name="prefecture"
                           value="{{ old('prefecture', $painterProfile->prefecture) }}"
                           placeholder="例：東京都"
                           class="form-input">
                    @error('prefecture')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="activity_regions_input" class="form-label">活動エリア（任意・カンマ区切り）</label>
                    <input type="text" id="activity_regions_input" name="activity_regions_input"
                           value="{{ old('activity_regions_input', is_array($painterProfile->activity_regions ?? null) ? implode(',', $painterProfile->activity_regions) : '') }}"
                           placeholder="例：東京都,神奈川県,千葉県"
                           class="form-input">
                    <p class="form-help">出張撮影が可能なエリアなど</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="years_active" class="form-label">活動年数</label>
                        <input type="number" id="years_active" name="years_active" min="0" max="80"
                               value="{{ old('years_active', $painterProfile->years_active) }}"
                               placeholder="例：5"
                               class="form-input">
                        @error('years_active')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 cursor-pointer pb-2.5">
                            <input type="checkbox" name="accepts_offers" value="1"
                                   {{ old('accepts_offers', $painterProfile->accepts_offers ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 border-secondary-400 text-secondary-900 focus:ring-secondary-700">
                            <span class="text-sm text-secondary-700">オファー受付中</span>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── 作風・専門 ── --}}
        <section class="border border-secondary-200 bg-canvas-50">
            <div class="px-5 py-3 border-b border-secondary-200">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Style</p>
            </div>
            <div class="p-5 sm:p-6 space-y-5">
                <div>
                    <label for="art_styles_input" class="form-label">アートスタイル（カンマ区切り）</label>
                    <input type="text" id="art_styles_input" name="art_styles_input"
                           value="{{ old('art_styles_input', is_array($painterProfile->art_styles ?? null) ? implode(',', $painterProfile->art_styles) : '') }}"
                           placeholder="例：油絵,デッサン,水彩画"
                           class="form-input">
                    <p class="form-help">使用する技法やメディウム</p>
                </div>

                <div>
                    <label for="specialties_input" class="form-label">得意ジャンル（カンマ区切り）</label>
                    <input type="text" id="specialties_input" name="specialties_input"
                           value="{{ old('specialties_input', is_array($painterProfile->specialties ?? null) ? implode(',', $painterProfile->specialties) : '') }}"
                           placeholder="例：ポートレート,人物画,和装"
                           class="form-input">
                    <p class="form-help">モチーフ・テーマの専門分野</p>
                </div>
            </div>
        </section>

        {{-- ── 自己紹介・経歴 ── --}}
        <section class="border border-secondary-200 bg-canvas-50">
            <div class="px-5 py-3 border-b border-secondary-200">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">About</p>
            </div>
            <div class="p-5 sm:p-6 space-y-5">
                <div>
                    <label for="bio" class="form-label">自己紹介</label>
                    <textarea id="bio" name="bio" rows="5"
                              placeholder="制作の方向性・現在の活動など（2000文字以内）"
                              class="form-textarea">{{ old('bio', $painterProfile->bio) }}</textarea>
                    @error('bio')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="experience" class="form-label">経歴・受賞歴</label>
                    <textarea id="experience" name="experience" rows="5"
                              placeholder="個展・グループ展・受賞歴・教育歴など"
                              class="form-textarea">{{ old('experience', $painterProfile->experience) }}</textarea>
                    @error('experience')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        {{-- ── 外部リンク ── --}}
        <section class="border border-secondary-200 bg-canvas-50">
            <div class="px-5 py-3 border-b border-secondary-200">
                <p class="text-[10px] tracking-[0.3em] uppercase text-secondary-500">Links</p>
            </div>
            <div class="p-5 sm:p-6 space-y-5">
                <div>
                    <label for="portfolio_url" class="form-label">ポートフォリオURL</label>
                    <input type="url" id="portfolio_url" name="portfolio_url"
                           value="{{ old('portfolio_url', $painterProfile->portfolio_url) }}"
                           placeholder="https://example.com"
                           class="form-input">
                    @error('portfolio_url')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="sns_links_input" class="form-label">SNSリンク（カンマ区切り）</label>
                    <input type="text" id="sns_links_input" name="sns_links_input"
                           value="{{ old('sns_links_input', is_array($painterProfile->sns_links ?? null) ? implode(',', $painterProfile->sns_links) : '') }}"
                           placeholder="https://www.instagram.com/...,https://x.com/..."
                           class="form-input">
                    <p class="form-help">Instagram / X / その他のURLをカンマで区切って入力</p>
                </div>
            </div>
        </section>

        {{-- 送信ボタン --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('mypage') }}"
               class="order-2 sm:order-1 px-6 py-2.5 border border-secondary-400 text-secondary-700 text-xs uppercase tracking-[0.2em] hover:bg-secondary-100 transition-colors duration-200 text-center">
                Cancel
            </a>
            <button type="submit"
                    class="order-1 sm:order-2 px-8 py-2.5 bg-secondary-900 text-canvas-50 border border-secondary-900 text-xs uppercase tracking-[0.25em] hover:bg-canvas-50 hover:text-secondary-900 transition-colors duration-300">
                Save
            </button>
        </div>
    </form>
</div>
@endsection
