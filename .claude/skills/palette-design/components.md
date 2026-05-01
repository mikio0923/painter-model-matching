# コンポーネントクラス リファレンス

`resources/css/components.css` に定義済みの再利用可能クラス一覧。**これらが既にある場合は必ず再利用する**。

## レイアウト

### `.page` / `.page-narrow`

ページ全体のラッパー。

```blade
<div class="page">         {{-- max-w-7xl --}}
<div class="page-narrow">  {{-- max-w-5xl --}}
```

両者とも `mx-auto px-4 sm:px-6 lg:px-8 py-10` を含む。

### `.section`
セクション間のマージン: `mb-16`

### `.section-panel` / `.section-panel-inner`
カード状のセクション枠。

```blade
<section class="section-panel">
    <div class="section-panel-inner">  {{-- p-6 sm:p-8 lg:p-10 --}}
        ...
    </div>
</section>
```

### `.section-header`
タイトル + 「すべて見る」リンクの組み合わせ。

```blade
<div class="section-header">
    <div>
        <p class="section-title-en mb-2">Pickup Model</p>
        <h2 class="section-title">注目のモデル</h2>
    </div>
    <a href="..." class="link-arrow">すべて見る</a>
</div>
```

## タイポグラフィ

### `.section-title`
セクションの大見出し。`font-display` + `clamp(1.75rem, 4vw, 2.75rem)`。

### `.section-title-en`
英字の小ラベル（ピル状バッジ）。バイオレット背景・白文字。

### `.section-title-en-gold`
ゴールド版のピルバッジ。Pickup や特別セクションで使用。

### `.section-subtitle`
サブタイトル: `text-lg font-semibold text-secondary-700`

## ボタン

### プライマリ系（バイオレット）

| クラス | サイズ | 用途 |
|--------|--------|------|
| `.btn-primary` | 標準 | メインアクション |
| `.btn-primary-sm` | 小 | カード内・テーブル内 |
| `.btn-primary-lg` | 大 | ヒーローCTA |

### セカンダリ（白アウトライン）

| クラス | 用途 |
|--------|------|
| `.btn-secondary` | サブアクション・キャンセル |
| `.btn-secondary-sm` | 小サイズ |

### アウトライン（バイオレット枠）

| クラス | 用途 |
|--------|------|
| `.btn-outline` | 控えめなメインアクション |
| `.btn-outline-sm` | 小サイズ |

### その他

| クラス | 用途 |
|--------|------|
| `.btn-accent` | クリムゾン（モデル系） |
| `.btn-ghost` | 透明背景・ナビ系 |
| `.btn-danger` | 削除・退会 |
| `.btn-danger-sm` | 小サイズ |
| `.btn-gray` | `.btn-secondary` のエイリアス（後方互換） |

**ボタン選択ルール:**
- メインCTA = primary
- サブ = secondary  
- 危険 = danger
- ナビ・微妙な操作 = ghost
- 同一画面に primary を **2つ以上置かない**（迷わせる）

## カード

### `.card` （汎用）
標準カード。`bg-white rounded-2xl shadow-card` + ホバーで浮き上がる。

```blade
<div class="card">
    <div class="card-body">{{-- p-5 sm:p-6 --}}
        ...
    </div>
</div>
```

オプション:
- `.card-hover` — ホバー時の浮き＋影
- `.card-media` — 画像エリア用ラッパー
- `.card-title` — タイトル
- `.card-meta` — 補助情報
- `.card-price` — 価格表示
- `.card-header` / `.card-footer` — ヘッダー/フッター

### `.model-card` (モデルカード専用)
**aspect-ratio 2/3 固定の縦長ポートレート**。

```blade
<div class="model-card model-card-female">  {{-- 性別ボーダー --}}
    <div class="model-card-image">
        <img src="..." alt="...">
        <div class="model-card-overlay"></div>
    </div>
    <div class="model-card-info">
        <p class="card-title">...</p>
    </div>
</div>
```

性別バリアント:
- `.model-card-male` — 青ボーダー
- `.model-card-female` — ローズボーダー
- `.model-card-other` — グレーボーダー

選択方法:
```php
$genderClass = match($model->gender ?? '') {
    'male'   => 'model-card-male',
    'female' => 'model-card-female',
    default  => 'model-card-other',
};
```

### `.job-card` (求人カード専用)
**flex-col で高さ均等**。footer を `mt-auto` で下端に固定する設計。

```blade
<div class="job-card">
    <a href="..." class="job-card-body">  {{-- p-5 flex flex-col flex-1 --}}
        <h3>...</h3>
        <p>...</p>
        <div class="mt-auto">{{-- 必須：footer をカード底に --}}
            ...
        </div>
    </a>
</div>
```

## フォーム

| クラス | 用途 |
|--------|------|
| `.form-input` | テキスト入力（標準） |
| `.form-select` | セレクトボックス |
| `.form-textarea` | テキストエリア |
| `.form-label` | ラベル |
| `.form-error` | エラーメッセージ（赤・小） |
| `.form-help` | 補助テキスト（グレー・小） |

```blade
<div>
    <label for="name" class="form-label">名前</label>
    <input type="text" id="name" name="name" class="form-input">
    @error('name')
        <p class="form-error">{{ $message }}</p>
    @enderror
    <p class="form-help">本名でなくて構いません</p>
</div>
```

## バッジ・タグ

### `.badge` (汎用ベース) + バリアント

| クラス | 色 | 用途 |
|--------|-----|------|
| `.badge-primary` | violet | カテゴリ |
| `.badge-accent` | crimson | 注目・優先 |
| `.badge-gold` | gold | プレミアム・Pickup |
| `.badge-success` | green | 公開中・承認済み |
| `.badge-warning` | amber | 審査中 |
| `.badge-error` | red | 差し戻し・エラー |
| `.badge-secondary` | gray | 中立・補助 |

### `.tag` (スタイルタグ・ジャンル)
```blade
<span class="tag">ポートレート</span>
```

### ステータスバッジ（求人専用）
- `.status-open` (公開中・緑)
- `.status-closed` (締切・グレー)
- `.status-done` (完了・バイオレット)

## リンク

| クラス | 用途 |
|--------|------|
| `.link-primary` | メインリンク（下線・バイオレット） |
| `.link-secondary` | 補助リンク（グレー） |
| `.link-arrow` | 矢印付きリンク（「すべて見る →」） |

## ヒーロー

### `.hero` / `.hero-content`
最上部のキービジュアル。`bg-hero-gradient` を背景に。

### `.hero-title` / `.hero-subtitle`
タイポは display フォントで `clamp()` 可変。

## ページヘッダー（内部ページ用）

### `.page-header` / `.page-header-inner`
ダーク背景の上部バナー（依頼一覧・モデル一覧用）。

```blade
<div class="page-header">
    <div class="page-header-inner">
        <p class="page-header-subtitle">SECTION</p>
        <h1 class="page-header-title">タイトル</h1>
    </div>
</div>
```

## アバター

```blade
<div class="avatar avatar-md">  {{-- avatar-sm/md/lg/xl --}}
    <img src="..." alt="...">
</div>
```

サイズ:
- `.avatar-sm` w-8 h-8 (32px)
- `.avatar-md` w-12 h-12 (48px)
- `.avatar-lg` w-16 h-16 (64px)
- `.avatar-xl` w-24 h-24 (96px)

## お気に入りボタン

```blade
{{-- 未登録 --}}
<button class="fav-btn">
    <svg><!-- heart outline --></svg>
</button>

{{-- 登録済み --}}
<button class="fav-btn-active">
    <svg><!-- heart filled --></svg>
</button>
```

## ディバイダー（装飾区切り）

```blade
<div class="divider-ornament">
    <span>OR</span>
</div>
```

水平線の中央にラベル。控えめに使用。

## ユーティリティ

- `.scrollbar-hide` — スクロールバー非表示

## クラス選択フローチャート

新しいUIを作る時の判断順:

```
1. 画像メインのカード → model-card or job-card
2. 一般カード → card + card-body
3. ボタン → btn-{primary,secondary,outline,accent,ghost,danger}
4. フォーム → form-{input,select,textarea}
5. バッジ → badge + badge-{color}
6. リンク → link-{primary,secondary,arrow}
7. レイアウト → page or page-narrow
8. セクション枠 → section-panel + section-panel-inner
```

該当クラスがない時のみ Tailwind 直書き、それも 3箇所以上で使うなら必ずコンポーネント化。
