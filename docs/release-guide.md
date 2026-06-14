# Palette 本番リリースガイド

最終更新: 2026-06-12

---

## このガイドについて

Palette を本番環境に公開するための作業手順をまとめたものです。
方針転換後（決済機能・本人確認機能を停止）の現行構成に合わせています。

---

## 現在の機能スコープ

### ✅ 実装済み・本番投入可能
- 画家・モデル登録 / ログイン（カスタム login-register 画面）
- モデルプロフィール（公開/非公開、画像、自己紹介、活動条件等）
- 画家プロフィール
- 依頼の投稿・編集・削除（画家）
- 依頼の検索・閲覧（モデル）
- 応募 / 承認 / 却下フロー
- 1案件1ペア間のメッセージ機能
- お気に入り（モデル・依頼）
- 通知
- レビュー
- お問い合わせ
- お知らせ（管理画面から投稿）
- アカウント設定（メール/パスワード変更、退会）マイページ統合
- 管理画面（ユーザー・依頼・お問い合わせ・お知らせ管理、全日本語化）
- ページネーション（10ページ単位の窓表示）
- 600件超のシーディング動作確認済み

### 🚫 一旦停止（コード残置・コメントアウト）
- 本人確認（書類提出）機能
  - 復活時：`routes/web.php` の `identity-verification` 系をアンコメント
  - 管理画面ナビ・モデルマイページの該当箇所もコメントアウト解除

### ❌ 機能として持たない（削除済み）
- 決済機能（モデル有料オプション・カード登録）
- 画家↔モデル間の取引代金代行
- → 報酬は当事者間で直接やり取り（ガイドラインに明記）

---

## リリースまでの必須タスク

### 🔴 1. 本番環境変数の差し替え

`.env.production` をコピーして `.env` を作成し、`CHANGE_ME_*` を実値に置き換える。

```bash
cp src/.env.production src/.env
```

#### 差し替えが必要な項目
| 項目 | 値の例 |
|---|---|
| `APP_KEY` | `php artisan key:generate` で生成 |
| `APP_URL` | `https://palette.example.com` |
| `DB_HOST` | 本番 DB ホスト |
| `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | 本番 DB 認証情報 |
| `MAIL_FROM_ADDRESS` | `noreply@yourdomain.com` |
| `MAIL_HOST` / `MAIL_USERNAME` / `MAIL_PASSWORD` | メール送信サービスの認証 |
| `SENTRY_DSN` | Sentry プロジェクト作成後の DSN |
| `AWS_*` | S3 を画像保管に使う場合 |

### 🔴 2. メール送信サービスの設定

`.env.production` 既定は AWS SES。**個人開発なら Resend が楽**。

#### Resend を使う場合
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=re_xxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME=Palette
```

#### DNS 設定（必須）
- SPF レコード（`v=spf1 include:_spf.resend.com ~all`）
- DKIM レコード（Resend ダッシュボード参照）
- DMARC レコード（`v=DMARC1; p=none; rua=mailto:dmarc@yourdomain.com`）

これがないと Gmail でスパム判定される。

### 🔴 3. 本番ホスティング

#### 推奨スタック（個人開発・低コスト）
- **VPS**: Xserver VPS 2GB プラン（約 ¥900/月）
- **ドメイン**: お名前.com `.com`（年 ¥1,200）
- **CDN/SSL**: Cloudflare 無料プラン
- **エラー監視**: Sentry 無料枠
- **稼働監視**: UptimeRobot 無料

#### デプロイ前の作業
- [ ] サーバーに PHP 8.3 / MySQL 8.0 / Node 20 / Composer 2 をインストール
- [ ] ドメインの DNS を Cloudflare 経由でサーバーに向ける
- [ ] Cloudflare SSL を「Full (strict)」に設定
- [ ] サーバーに `git clone` でデプロイ
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `npm ci && npm run build`
- [ ] `php artisan migrate --force`
- [ ] `php artisan db:seed --class=AdminUserSeeder`
- [ ] `php artisan storage:link`
- [ ] `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- [ ] ストレージ・ログのパーミッション設定（`chmod -R 775 storage bootstrap/cache`）
- [ ] cron に `php artisan schedule:run` を毎分登録

### 🟡 4. 初期管理者の設定

```bash
php artisan db:seed --class=AdminUserSeeder
```

デフォルト：
- メール: `admin@example.com`
- パスワード: `password`

**本番ではログイン直後に変更必須**。

### 🟡 5. デモデータの取扱い

開発時に流した 622 件のモデルデータは本番には流さない。
本番マイグレーション後は **管理者だけ作成**して、実ユーザーの登録を待つ。

---

## デプロイコマンド一覧（参考）

```bash
# 初回デプロイ
git clone https://github.com/yourname/painter-model-matching.git
cd painter-model-matching/src
composer install --no-dev --optimize-autoloader
cp ../.env.production .env  # CHANGE_ME を編集
php artisan key:generate
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder
php artisan storage:link
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 更新デプロイ
cd painter-model-matching/src
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## リリース後にすぐ確認すること

### 動作チェック
- [ ] トップページが表示される
- [ ] 新規モデル登録 → ログイン → プロフィール作成ができる
- [ ] 新規画家登録 → ログイン → 依頼作成ができる
- [ ] モデルが依頼を検索・応募できる
- [ ] 画家が応募を承認 → メッセージが開通する
- [ ] お問い合わせから連絡できる
- [ ] 管理画面にログインできる
- [ ] メール送信が実際に届く（MailHog ではなく実メーラー経由で）

### セキュリティ
- [ ] 管理者パスワードを変更
- [ ] `APP_DEBUG=false` 確認
- [ ] `APP_KEY` が生成済み
- [ ] HTTPS で正常にアクセスできる
- [ ] `.env` がリポジトリに含まれていない
- [ ] DB バックアップが動いている

### 監視
- [ ] Sentry にテスト例外を投げて受信確認
- [ ] UptimeRobot で死活監視設定（5分間隔推奨）
- [ ] Cloudflare の WAF が有効

---

## トラブルシューティング

### マイグレーションでエラー
```bash
php artisan migrate:rollback
# ログを確認して原因特定後に再 migrate
```

### Tailwind が反映されない
```bash
npm run build
php artisan view:clear
```

### キャッシュが古い
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### メールが届かない
- DNS（SPF/DKIM/DMARC）を `dig` で確認
- メールサービスのダッシュボードで送信ログを確認
- Gmail の場合、迷惑メールフォルダもチェック

---

## リリース後の運用

### 日次
- Sentry のエラー通知を確認
- UptimeRobot の稼働率を確認

### 週次
- DB バックアップが取れていることを確認
- 新規登録ユーザー数・依頼数の推移を確認

### 月次
- Composer / npm の脆弱性チェック（`composer audit` / `npm audit`）
- Laravel / PHP のセキュリティアップデート確認

---

## 既知の制限

- **メッセージ機能**：当事者間の連絡用。長期保管が必要なやり取りには SES 等のメール通知も使われる想定
- **画像最適化**：現状サンプル画像が大きい。本番投入時は WebP 変換 + リサイズを検討
- **検索**：MySQL の LIKE 検索のため、ユーザー数が数十万を超えるとパフォーマンス低下の可能性
- **本人確認**：機能停止中。利用者は自己責任で相手を見極める前提（ガイドライン参照）
