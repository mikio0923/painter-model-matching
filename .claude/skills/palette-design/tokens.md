# デザイントークン リファレンス

すべて `tailwind.config.js` で定義済み。**生の hex 値を Blade に書かない**。必ずトークン名を使う。

## カラーパレット

### Primary（バイオレット）— ブランド主軸 / メインアクション

| キー | hex | 用途 |
|------|-----|------|
| primary-50 | #f5f3ff | ごく薄い背景・hover時の弱アクセント |
| primary-100 | #ede9fe | バッジ背景 (`badge-primary`) |
| primary-300 | #c4b5fd | ボーダー |
| primary-500 | #8b5cf6 | サブアクション |
| **primary-600** | **#7c3aed** | **メインボタン・ロゴ（基本これ）** |
| primary-700 | #6d28d9 | hover 状態 |
| primary-900 | #3b0764 | ヒーロー背景の暗部 |

**使う場面**: ログイン、検索、保存、応募、メインCTAなど

### Accent（クリムゾン）— モデル / 情熱 / 重要警告

| キー | hex | 用途 |
|------|-----|------|
| accent-100 | #ffe4e6 | バッジ背景 |
| accent-500 | #f43f5e | お気に入り（active）|
| **accent-600** | **#e11d48** | **モデル向けボタン** |
| accent-700 | #be123c | hover |

**使う場面**: お気に入りハート、モデル系アクション、危険操作の警告

### Gold（アンバー）— Pickup / プレミアム / 信頼

| キー | hex | 用途 |
|------|-----|------|
| gold-100 | #fef3c7 | バッジ背景 |
| gold-500 | #f59e0b | 星評価 |
| **gold-600** | **#d97706** | **Pickup ラベル** |

**使う場面**: 「Pickup Model」「高評価レビュー」「信頼バッジ」

### Canvas（パーチメント）— ベース背景

| キー | hex | 用途 |
|------|-----|------|
| **canvas-50** | **#fdfaf6** | **body の背景（基本）** |
| canvas-100 | #faf4ec | 控えめなセクション背景 |
| canvas-200 | #f3e6d5 | カード区切り |

**使う場面**: ページ全体の背景。白(#ffffff)でなく canvas-50 を使うことで温かみが出る。

### Secondary（ウォームストーン）— テキスト / ボーダー

| キー | hex | 用途 |
|------|-----|------|
| secondary-50 | #fafaf9 | 微妙な背景区別 |
| secondary-100 | #f5f5f4 | hover 背景・バッジ |
| secondary-200 | #e7e5e4 | 標準ボーダー |
| secondary-300 | #d6d3d1 | 強めのボーダー |
| secondary-400 | #a8a29e | プレースホルダ・補助テキスト |
| secondary-500 | #78716c | サブテキスト |
| secondary-600 | #57534e | サブヘッダー |
| secondary-700 | #44403c | 標準テキスト |
| **secondary-900** | **#1c1917** | **メインテキスト・見出し** |

### ステータスカラー

| 用途 | キー | 使う場面 |
|------|------|---------|
| 成功 | success-100/600/700 | 承認・登録完了・「公開中」 |
| 警告 | warning-100/600 | 審査中・注意喚起 |
| エラー | error-100/600/700 | バリデーション・削除・差し戻し |

## タイポグラフィ

### フォントファミリー

```css
font-sans     /* 'Noto Sans JP', Inter, ...defaultTheme — 本文・UI */
font-display  /* 'Cormorant Garamond', 'Noto Serif JP', Georgia, serif — 大見出し */
```

### サイズスケール（行間込み）

| Tailwind | フォントサイズ | line-height | 用途 |
|----------|---------------|-------------|------|
| `text-xs` | 0.75rem (12px) | 1.5 | バッジ・キャプション |
| `text-sm` | 0.875rem (14px) | 1.5 | UI ラベル・補助 |
| `text-base` | 1rem (16px) | 1.75 | 本文標準 |
| `text-lg` | 1.125rem (18px) | 1.75 | サブヘッド |
| `text-xl` | 1.25rem (20px) | 1.6 | カードタイトル |
| `text-2xl` | 1.5rem (24px) | 1.4 | セクションサブ |
| `text-3xl` | 1.875rem (30px) | 1.3 | ページタイトル |
| `text-4xl` | 2.25rem (36px) | 1.2 | 大見出し |
| `text-5xl` | 3rem (48px) | 1.1 | ヒーロー（小） |
| `text-6xl` | 3.75rem (60px) | 1.05 | ヒーロー（大） |

### フルードタイポグラフィ（推奨）

大見出しは `clamp()` で可変にする：

```css
font-size: clamp(2.5rem, 6vw, 4.5rem);   /* hero-title */
font-size: clamp(1.75rem, 4vw, 2.75rem); /* section-title */
```

### letter-spacing

| 値 | 用途 |
|------|------|
| `tracking-widest` | 英字の小ラベル（`SECTION TITLE EN`） |
| `tracking-tight` | 大見出しのタイトニング |
| カスタム `-0.02em` | display フォントの大見出し |
| カスタム `-0.03em` | section-title 用 |

## スペーシング

| Tailwind | px | 用途 |
|----------|-----|------|
| `p-2` | 8px | アイコンボタン内 |
| `p-3` | 12px | 小さなカード内 |
| `p-4` | 16px | 標準カード内 |
| `p-5 sm:p-6` | 20px → 24px | カード（推奨） |
| `p-6 sm:p-8 lg:p-10` | 24 → 32 → 40 | section-panel-inner |
| `py-10` | 40px | ページ縦余白 |
| `py-20 sm:py-28 lg:py-36` | 80→112→144 | ヒーロー |
| `space-y-20` | 80px | セクション間（基本） |

カスタム拡張：`spacing-18` (4.5rem), `spacing-88` (22rem), `spacing-128` (32rem)

## 角丸

| Tailwind | 半径 | 用途 |
|----------|------|------|
| `rounded-md` | 6px | タグ |
| `rounded-lg` | 8px | 小ボタン・小カード |
| `rounded-xl` | 12px | 標準ボタン・入力欄 |
| `rounded-2xl` | 16px | カード（標準） |
| `rounded-3xl` | 24px | 大きなパネル（控えめに使う） |
| `rounded-full` | 円 | アバター・バッジのみ |

## シャドウ

| キー | 強さ | 用途 |
|------|------|------|
| `shadow-sm` | 弱 | ボタン・小カード |
| `shadow-card` | 中 | **カード（推奨）** |
| `shadow-card-hover` | 強 | カードのホバー |
| `shadow-soft` | 拡散 | 下方向への弱拡散 |
| `shadow-glow` | バイオレット | プライマリボタンのhover |
| `shadow-glow-accent` | クリムゾン | アクセントボタンのhover |

**`shadow-2xl` 等の標準Tailwindシャドウは使わない**。カスタムを優先。

## グラデーション

| 名前 | 値 | 用途 |
|------|-----|------|
| `bg-hero-gradient` | violet → crimson 斜め | メインヒーロー |
| `bg-hero-gradient-subtle` | dark stone → violet 控えめ | 内ページのヘッダー |
| `bg-card-gradient` | 透明 → 黒 | 画像オーバーレイ |
| `bg-gold-gradient` | amber → orange | プレミアムバッジ |

## アニメーション

```css
animation: fade-in 0.4s ease-out
animation: slide-up 0.4s ease-out
transition-timing: ease-smooth (cubic-bezier(0.4, 0, 0.2, 1))
```

カスタム class: `animate-fade-in` `animate-slide-up`

## トークン使用例

```blade
{{-- ❌ ダメ --}}
<div style="background: #7c3aed; color: white; padding: 16px;">

{{-- ✅ 正解 --}}
<div class="bg-primary-600 text-white p-4">
```

```blade
{{-- ❌ ダメ（独自影） --}}
<div class="bg-white shadow-2xl rounded-3xl p-10">

{{-- ✅ 正解 --}}
<div class="bg-white shadow-card rounded-2xl p-6 sm:p-8">
```
