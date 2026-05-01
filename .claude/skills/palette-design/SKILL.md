---
name: palette-design
description: Palette（画家×モデルマッチングサイト）のデザインシステム。Blade ビューの作成・修正、CSS 編集、UI コンポーネント追加など、視覚に関わる作業時に必ず参照する。
---

# Palette デザインシステム

このプロジェクトは画家とモデルをつなぐマッチングプラットフォーム。デザインの方向性は「**美術館の静謐 × アーティストのポートフォリオ**」。**おとなしく、上質で、邪魔をしない**雰囲気の中で、マッチング機能を主役にする。

## いつこのスキルを使うか

以下の作業時は必ずこのスキルの内容に従う：
- `resources/views/**/*.blade.php` の作成・編集
- `resources/css/components.css` の編集
- `tailwind.config.js` のテーマ拡張
- 新規ページ・新規コンポーネントの追加
- UI/UX に関する提案・改善

## 設計哲学（最重要）

### コンセプト: Quiet Museum

「ファッション誌の表紙のような派手さ」ではなく、「美術館のホワイトキューブの静けさ」を目指す。

**参照イメージ:**
- **MoMA や 国立新美術館** のような白い壁・余白の使い方
- **アート系オークションカタログ**（Sotheby's / Christie's）の落ち着いたタイポグラフィ
- **Apollo Magazine / Frieze 誌** のクラシカルなレイアウト
- 背景に**名画（富嶽三十六景・印象派など public domain）**が穏やかに動く演出

### 三原則

1. **Whisper, don't shout** — 主張を抑える。色・影・装飾はすべて控えめに
2. **Art is atmosphere** — 名画やポートフォリオは**背景**として漂わせ、UIを邪魔しない
3. **Function first, beauty quietly** — マッチングサイトであることを忘れない。美しさは結果として滲み出るもの

### やってはいけないこと（Anti-patterns）

- ❌ **派手なグラデーションを使わない**（特にネオン系のviolet→crimson）
- ❌ **彩度の高い色を多用しない**（くすんだ色味を優先）
- ❌ 影を強くしすぎない（`shadow-card` まで）
- ❌ 角を丸くしすぎない（`rounded-xl` `rounded-2xl` まで）
- ❌ 絵文字をUIに使わない
- ❌ アニメーションを速くしすぎない（`duration-700`〜`duration-1000` を基本）
- ❌ 同一画面に同時に動く要素は**最大2つ**
- ❌ 背景の名画が UI を邪魔する透過度（**5〜12% opacity が上限**）

## カラー戦略（更新版）

派手なバイオレット/クリムゾンは**特別な場面のみ**使用に格下げ。基本配色は墨・パーチメント・抑えた金。

### メインパレット

| 用途 | 推奨トークン | 説明 |
|------|------------|------|
| **背景（ベース）** | `bg-canvas-50` (#fdfaf6) | 紙・パーチメント色 |
| **テキスト** | `text-secondary-900` (#1c1917) | 墨色 |
| **サブテキスト** | `text-secondary-500/600` | スモーキー |
| **ボーダー** | `border-secondary-200` | 薄い |
| **メインアクション** | `text-secondary-900 bg-secondary-900` | **黒ボタン**（落ち着き） |
| **控えめなアクセント** | `text-gold-700` `border-gold-600` | 抑えた金 |
| **危険操作** | `text-error-600` (微) | 過剰でない赤 |

### 使うべきでないパターン

```blade
{{-- ❌ 派手すぎる（旧テーマ） --}}
<div class="bg-hero-gradient">  {{-- violet→crimson の派手なグラデーション --}}
<a class="btn-primary">         {{-- ビビッドなバイオレット --}}

{{-- ✅ 美術館トーン --}}
<div class="bg-canvas-50">       {{-- パーチメント背景 --}}
<a class="btn-museum-dark">     {{-- 黒ボタン（new） --}}
```

### 残しておく派手色の使い場所

- `primary-600` (バイオレット) → 「お気に入り」「重要通知」「エラー警告」のみ
- `accent-600` (クリムゾン) → 削除等の危険操作のみ
- `gold-600` → Pickup/プレミアム表示のみ。**メインカラーには昇格しない**

## タイポグラフィ階層

クラシカル寄り、より優雅に。

### 必須ルール

- **大見出し**: `font-display` (Cormorant Garamond) — letter-spacing をやや広めに
- **本文**: `font-sans` (Noto Sans JP)
- **キャプション・キュレーター注釈**: `font-display italic` — 美術館ラベル風

### 階層

```
hero-title         → font-display, clamp(2.25rem, 5vw, 3.75rem)（旧より小さめ）
section-title      → font-display, clamp(1.5rem, 3.5vw, 2.25rem)
section-title-en   → 英字小ラベル（uppercase tracking-[0.3em]、※ピル形状はやめる）
art-caption        → font-display italic, text-sm, text-secondary-500
本文                → font-sans, leading-relaxed
```

### section-title-en のスタイル変更

旧: ピル状の violet 背景バッジ
**新:** 黒文字 + 上下にヘアライン、超ワイドなトラッキング（美術館ラベル風）

```blade
<p class="text-[10px] font-medium tracking-[0.3em] uppercase text-secondary-500 border-y border-secondary-200 inline-block py-1 px-3 mb-3">
    Pickup Model
</p>
```

## レイアウト原則

### 余白を贅沢に

- セクション間: `space-y-24` または `space-y-32`（旧 `space-y-20` より広く）
- カード内余白: `p-6 sm:p-8`（旧より広く）
- 画像の周りに必ず余白を取る（フチなしで詰めない）

### コンテナ
- `.page` (max-w-7xl) — 通常
- `.page-narrow` (max-w-5xl) — 記事系
- 美術館感を出すなら `max-w-6xl` も選択肢

## 写真・アート画像の扱い

### モデル写真（変更なし）
- `aspect-ratio: 2/3` 縦長ポートレート
- `object-cover` で固定
- ホバー: `group-hover:scale-105` の軽い拡大

### 背景アートレイヤー（NEW）

ヒーロー・各セクションの背景に名画を**極薄く**敷く演出。

**ルール:**
- 透過度 `opacity: 0.05〜0.12`（**12%超えない**）
- アニメーション: 30〜60秒の Ken Burns エフェクト（slow zoom + pan）
- グレースケール or 軽いセピア処理推奨
- モバイルでは無効化（パフォーマンス）
- 詳細パターンは [patterns.md](./patterns.md) の「背景アートレイヤー」を参照

### 使う public domain の名画候補

- 葛飾北斎「富嶽三十六景」(神奈川沖浪裏 / 凱風快晴 など)
- 歌川広重「名所江戸百景」
- ゴッホ「星月夜」「ひまわり」
- モネ「睡蓮」「印象・日の出」
- フェルメール「真珠の耳飾りの少女」
- ターナー、ルノワール、ドガ等の19世紀以前の作品

すべて `public/images/art-bg/` にローカル保存して使用（外部URL直リンクは避ける）。

## アニメーション原則（速度を遅く）

| シーン | 速度 |
|------|------|
| ボタンhover | `duration-200`（変更なし） |
| 通常UI | `duration-300〜500` |
| **背景アート** | `duration-[60s]` 以上（極ゆっくり） |
| ページ遷移演出 | `duration-700` |

旧テーマでは `transition duration-200` が基本だったが、新テーマは**ホバー以外を遅く**して落ち着きを出す。

## モバイル原則

- モバイルファースト（変更なし）
- **背景アートレイヤーはモバイルでは無効化**（パフォーマンス・読みやすさ優先）
- タッチターゲット最小 44x44px

## 関連ドキュメント

- **[tokens.md](./tokens.md)** — カラー、タイポ、シャドウ、スペーシング全リスト
- **[components.md](./components.md)** — `.btn-*` `.card` `.section-*` 等のクラス一覧
- **[patterns.md](./patterns.md)** — UI 構成レシピ集（背景アートレイヤー含む）
- **[voice-tone.md](./voice-tone.md)** — 文言・トーン

## 作業前チェックリスト

新規 UI を作る時：

1. ☐ そのページに**派手な色**は本当に必要か？（基本は墨と生成り）
2. ☐ [tokens.md](./tokens.md) に該当トークンがあるか
3. ☐ [components.md](./components.md) に既存クラスがないか
4. ☐ [patterns.md](./patterns.md) のレシピが使えないか
5. ☐ 背景にアートを敷くなら、UIの可読性は確保されているか
6. ☐ アニメーションは「呼吸感」のある速度か（速すぎないか）

## 編集時のルール

- **派手な色を新たに増やさない**。既存トークン内で表現
- 新コンポーネントは [components.css](../../../src/resources/css/components.css) の `@layer components` 内に追加
- 一時的なスタイルは Blade の class 直書きで OK
- カラーは必ず Tailwind トークン（直書きhex禁止）
