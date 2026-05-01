# UIパターン レシピ集

よく使う構成のテンプレート。**新規ページを作る時はまずここを参照**。

## 1. ページ全体の骨格

### 標準ページ

```blade
@extends('layouts.app')

@section('content')
<div class="page space-y-20">

    {{-- セクション1 --}}
    <section class="animate-fade-in">
        <div class="section-header">
            <div>
                <p class="section-title-en mb-2">Section Label</p>
                <h2 class="section-title">セクションタイトル</h2>
            </div>
            <a href="..." class="link-arrow text-sm">
                すべて見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
        {{-- コンテンツ --}}
    </section>

    {{-- セクション2 ... --}}

</div>
@endsection
```

### 内部ページ（記事系）

```blade
@extends('layouts.app')

@section('content')
<div class="page-narrow">
    <nav class="text-sm text-secondary-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-secondary-700">ホーム</a>
        <span class="mx-1">/</span>
        <span class="text-secondary-800">現在のページ</span>
    </nav>

    <section class="section-panel">
        <div class="section-panel-inner">
            <div class="mb-8">
                <p class="section-title-en mb-3">Page Label</p>
                <h1 class="section-title">ページタイトル</h1>
                <p class="text-secondary-500 mt-3">説明文</p>
            </div>

            {{-- コンテンツ --}}
        </div>
    </section>
</div>
@endsection
```

## 2. ヒーローセクション

### スタイリッシュなグラデーションヒーロー

```blade
<section class="hero" style="background-image: linear-gradient(135deg, #1e0a3c 0%, #3b0764 30%, #6d28d9 65%, #be123c 100%);">
    {{-- 装飾ドット --}}
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>

    <div class="hero-content text-center">
        <p class="font-display tracking-[0.2em] text-xs sm:text-sm uppercase text-white/50 mb-4">
            Sub Tagline (English)
        </p>
        <h1 class="hero-title mb-5">
            メインメッセージ
        </h1>
        <p class="hero-subtitle mx-auto text-center">
            サブテキスト
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="..." class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white text-primary-800 font-bold text-base rounded-xl shadow-lg hover:bg-canvas-50 hover:-translate-y-0.5 transition-all duration-200">
                CTA1
            </a>
            <a href="..." class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/10 backdrop-blur-sm text-white font-bold text-base rounded-xl border border-white/30 hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-200">
                CTA2
            </a>
        </div>
    </div>

    {{-- 下部ウェーブ（ヒーローと本文の境界を柔らかく） --}}
    <div class="absolute bottom-0 left-0 right-0 overflow-hidden leading-none">
        <svg viewBox="0 0 1440 56" preserveAspectRatio="none" class="w-full h-14 block">
            <path d="M0,32 C240,56 480,0 720,28 C960,56 1200,8 1440,32 L1440,56 L0,56 Z" fill="#fdfaf6"/>
        </svg>
    </div>
</section>
```

## 3. モデルカードグリッド

```blade
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
    @foreach($models as $model)
    @php
        $genderClass = match($model->gender ?? '') {
            'male'   => 'model-card-male',
            'female' => 'model-card-female',
            default  => 'model-card-other',
        };
    @endphp
    <div class="model-card {{ $genderClass }} group">
        <a href="{{ route('models.show', $model) }}" class="block">
            <div class="model-card-image">
                @if($model->profile_image_path)
                    <img src="{{ Storage::url($model->profile_image_path) }}" alt="{{ $model->display_name }}" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-accent-100">
                        <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                @endif
                <div class="model-card-overlay"></div>
            </div>
            <div class="model-card-info">
                <p class="card-title text-sm flex items-center gap-1">
                    <span class="truncate">{{ $model->display_name }}</span>
                    @if($model->identity_verified)
                        <svg class="w-3.5 h-3.5 text-success-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </p>
                <p class="card-meta">{{ $model->prefecture }} · {{ $model->age }}歳</p>
            </div>
        </a>
    </div>
    @endforeach
</div>
```

## 4. 求人カード（高さ均等）

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($jobs as $job)
    <div class="job-card group">
        <a href="{{ route('jobs.show', $job) }}" class="block job-card-body">
            {{-- ヘッダー: 画家情報 --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="avatar avatar-md border-2 border-primary-100">
                    <img src="..." alt="..." class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-secondary-800 truncate">{{ $job->painter->name }}</p>
                    <p class="text-xs text-secondary-400">画家</p>
                </div>
                <div class="ml-auto">
                    <span class="status-open">公開中</span>
                </div>
            </div>

            {{-- 中央: タイトル + 概要 --}}
            <h3 class="text-base font-bold text-secondary-900 line-clamp-2 mb-2 leading-snug">
                {{ $job->title }}
            </h3>
            <p class="text-sm text-secondary-500 line-clamp-2 mb-4 leading-relaxed">
                {{ $job->description }}
            </p>

            {{-- フッター: 必ず mt-auto で底に --}}
            <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-secondary-100 mt-auto">
                <span class="inline-flex items-center gap-1 text-xs text-secondary-500">
                    <svg class="w-3.5 h-3.5">...</svg>
                    {{ $job->location_type === 'online' ? 'オンライン' : 'オフライン' }}
                </span>
                @if($job->reward_amount)
                <span class="ml-auto text-sm font-bold text-primary-600">
                    {{ number_format($job->reward_amount) }}円
                </span>
                @endif
            </div>
        </a>
    </div>
    @endforeach
</div>
```

## 5. フォーム

### スタンダードフォーム

```blade
<form method="POST" action="..." class="space-y-5">
    @csrf

    <div>
        <label for="name" class="form-label">名前 <span class="text-error-500">*</span></label>
        <input type="text" name="name" id="name" required class="form-input" value="{{ old('name') }}">
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="bio" class="form-label">自己紹介</label>
        <textarea name="bio" id="bio" rows="4" class="form-textarea">{{ old('bio') }}</textarea>
        <p class="form-help">2000文字以内</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 pt-2">
        <a href="..." class="btn-secondary">キャンセル</a>
        <button type="submit" class="btn-primary">保存</button>
    </div>
</form>
```

## 6. テーブル（管理画面用）

```blade
<div class="card">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-secondary-50 border-b border-secondary-200">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-secondary-700">列1</th>
                    <th class="text-left px-4 py-3 font-semibold text-secondary-700">列2</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary-100">
                @foreach($items as $item)
                    <tr class="hover:bg-secondary-50">
                        <td class="px-4 py-3">{{ $item->name }}</td>
                        <td class="px-4 py-3">
                            <a href="..." class="text-primary-600 hover:underline">操作</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

## 7. 空状態（Empty State）

```blade
<div class="card">
    <div class="card-body text-center py-12">
        <svg class="w-16 h-16 mx-auto text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <!-- アイコン -->
        </svg>
        <h3 class="text-lg font-semibold text-secondary-700 mb-2">まだ何もありません</h3>
        <p class="text-sm text-secondary-500 mb-6">最初の◯◯を追加してください</p>
        <a href="..." class="btn-primary inline-flex">追加する</a>
    </div>
</div>
```

## 8. アラート / 通知ボックス

### 情報・成功・警告・エラー

```blade
{{-- 成功 --}}
<div class="bg-success-50 border border-success-200 text-success-700 rounded-xl p-4 text-sm">
    操作が完了しました
</div>

{{-- 警告 --}}
<div class="bg-warning-50 border border-warning-200 text-warning-800 rounded-xl p-4 text-sm">
    ご注意ください
</div>

{{-- エラー --}}
<div class="bg-error-50 border border-error-200 text-error-700 rounded-xl p-4 text-sm">
    エラーが発生しました
</div>

{{-- 情報（薄バイオレット） --}}
<div class="bg-primary-50 border border-primary-200 text-primary-800 rounded-xl p-4 text-sm">
    情報をお知らせします
</div>
```

## 9. 詳細ページ（モデル/求人）

メインカラム + サイドバーのレイアウト：

```blade
<div class="page">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- メイン（左 2/3） --}}
        <div class="lg:col-span-2 space-y-6">
            ...
        </div>

        {{-- サイドバー（右 1/3、スティッキー） --}}
        <div>
            <div class="lg:sticky lg:top-24 lg:self-start space-y-6">
                <div class="card">
                    <div class="card-body">
                        ...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

## 10. 確認ダイアログ（削除など）

危険操作には必ず確認を：

```blade
<form method="POST" action="..." onsubmit="return confirm('本当に削除しますか？この操作は取り消せません。')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-danger">削除する</button>
</form>
```

## 11. ローディング・状態表示

### ボタンの無効化（処理中）

```blade
<button type="submit" class="btn-primary" :disabled="loading">
    <svg x-show="loading" class="animate-spin h-4 w-4 mr-2">...</svg>
    保存
</button>
```

### スケルトン（待機中）

```blade
<div class="animate-pulse">
    <div class="bg-secondary-200 rounded h-4 w-3/4 mb-2"></div>
    <div class="bg-secondary-200 rounded h-4 w-1/2"></div>
</div>
```

## 12. モーダル（Alpine.js）

```blade
<div x-data="{ open: false }">
    <button @click="open = true" class="btn-primary">開く</button>

    <div x-show="open" @click.away="open = false"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-2xl shadow-card-hover max-w-md w-full p-6">
            <h2 class="text-xl font-bold text-secondary-900 mb-4">タイトル</h2>
            <p class="text-secondary-600 mb-6">本文</p>
            <div class="flex justify-end gap-3">
                <button @click="open = false" class="btn-secondary">閉じる</button>
                <button class="btn-primary">確定</button>
            </div>
        </div>
    </div>
</div>
```

## 13. 背景アートレイヤー（美術館トーンの核）

ヒーローや特定セクションの背景に名画を**極めて薄く**敷き、Ken Burns 効果でゆっくり動かす。

### HTML 構造

```blade
<section class="relative overflow-hidden bg-canvas-50">
    {{-- 背景アートレイヤー（モバイルでは非表示） --}}
    <div class="absolute inset-0 hidden md:block pointer-events-none">
        <div class="art-bg-layer art-bg-1"></div>
        <div class="art-bg-layer art-bg-2"></div>
        <div class="art-bg-layer art-bg-3"></div>
        {{-- 上に薄いベール --}}
        <div class="absolute inset-0 bg-canvas-50/40"></div>
    </div>

    {{-- 実コンテンツ --}}
    <div class="relative z-10">
        ...
    </div>
</section>
```

### 必須CSS（components.css に追加済み）

各 `.art-bg-N` は別々の名画を敷き、ずらして fade-in/out を繰り返す。透過度は `0.05〜0.12` 以内。

### ルール
- 透過度 12% を超えない
- モバイル `md:` 未満では非表示
- 1セクションに1レイヤーセット（重ねすぎ禁止）
- ローディング軽減のため `loading="lazy"` または CSS `background-image` で

## 14. キュレーター・キャプション

美術館の作品ラベル風。アート画像や Pickup の補足に使う。

```blade
<div class="border-l-2 border-secondary-300 pl-4 max-w-md">
    <p class="font-display italic text-sm text-secondary-600 leading-relaxed">
        "ポートレートは、被写体と画家の対話の記録である"
    </p>
    <p class="text-xs text-secondary-400 mt-2 tracking-wider uppercase">
        — Curator's Note
    </p>
</div>
```

## レイアウト判断フローチャート

新ページ要件 → どのパターンを使うか:

```
ユーザーが何かを「閲覧」する → モデル/求人カードグリッド (パターン3 or 4)
ユーザーが何かを「入力」する → フォーム (パターン5)
ユーザーが「詳細」を見る   → 詳細ページ (パターン9)
管理者が「管理」する       → テーブル (パターン6)
お知らせ・規約系          → 内部ページ (パターン1の後者)
ランディング・ホーム       → ヒーロー + セクション (パターン1+2)
```
