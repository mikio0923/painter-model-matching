# Painter Model Matching プロジェクト

モデルと画家をマッチングするWebアプリケーション

## 技術スタック

### バックエンド
- **Laravel**: v12.0
- **PHP**: 8.3（Dockerコンテナ内）
- **MySQL**: 8.0（Dockerコンテナ内）

### フロントエンド
- **Vite**: v7.0.7（ビルドツール・開発サーバー）
- **Tailwind CSS**: v3.1.0
- **Alpine.js**: v3.4.2
- **Node.js**: v20.20.0以上（ローカル環境）

### インフラ
- **Docker Compose**: バックエンド（Laravel、MySQL、Nginx）をコンテナ化
- **Nginx**: ポート8081でアクセス

## 環境構成について

### Dockerコンテナで動いているもの
- **Laravelアプリケーション**（PHP 8.3-FPM）
- **MySQLデータベース**（ポート3307）
- **Nginx**（ポート8081）
- **Vite開発サーバー**（ポート5173）← Docker Composeで自動起動可能

### ローカル環境で動かすことも可能
- **Vite開発サーバー**（ポート5173）
  - Docker Composeを使わない場合は、ローカル環境で実行
  - ホットリロード（HMR）機能がより安定して動作する場合がある

## 前提条件

以下のソフトウェアがインストールされている必要があります：

1. **Docker Desktop**（またはDocker + Docker Compose）
2. **Node.js** v20.19以上 または v22.12以上
   - 推奨: nvmを使用してNode.jsを管理
3. **Composer**（PHP依存関係管理）
   - またはDockerコンテナ内で実行

## 初回セットアップ

### 1. リポジトリのクローン
```bash
git clone <repository-url>
cd painter-model-matching
```

### 2. Dockerコンテナの起動
```bash
docker-compose up -d
```

### 3. Laravel環境設定
```bash
# コンテナ内で実行
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
```

### 4. Node.js環境のセットアップ
```bash
# nvmを使用している場合
source ~/.nvm/nvm.sh
nvm install 20
nvm use 20

# プロジェクトディレクトリに移動
cd src

# 依存関係のインストール
npm install
```

### 5. 開発サーバーの起動

**ターミナル1: Vite開発サーバー**
```bash
cd src
source ~/.nvm/nvm.sh  # nvmを使用している場合
nvm use 20
npm run dev
```

**ターミナル2: Laravel開発サーバー（オプション）**
```bash
# Dockerコンテナを使用している場合は不要
# Nginxが既にポート8081で動作しているため
```

### 6. アクセス
- **アプリケーション**: http://localhost:8081
- **Vite開発サーバー**: http://localhost:5173（自動的にLaravelと連携）

## PC再起動後の起動手順

### 方法1: 自動起動スクリプトを使用（推奨）

**Docker ComposeでViteも自動起動する場合：**
```bash
# プロジェクトルートディレクトリで実行
./start-dev.sh
```

これで以下が自動的に起動します：
- Dockerコンテナ（Laravel、MySQL、Nginx、Vite）
- すべてのサービスが一度に起動

**ローカル環境でViteを起動する場合：**
```bash
# プロジェクトルートディレクトリで実行
./start-dev-local.sh
```

### 方法2: 手動で起動

#### 1. Dockerコンテナの起動
```bash
# プロジェクトルートディレクトリで実行
cd /path/to/painter-model-matching
docker-compose up -d
```

#### 2. コンテナの状態確認
```bash
docker-compose ps
```

以下のコンテナが起動していることを確認：
- `painter_app` (Laravel)
- `painter_nginx` (Nginx)
- `painter_db` (MySQL)
- `painter_vite` (Vite開発サーバー) ← Docker Composeで自動起動

#### 3. Vite開発サーバーの起動（Docker Composeを使わない場合のみ）
```bash
# プロジェクトのsrcディレクトリに移動
cd src

# nvmを使用している場合、Node.jsバージョンを設定
source ~/.nvm/nvm.sh
nvm use 20

# Vite開発サーバーを起動
npm run dev
```

#### 4. アクセス確認
- ブラウザで http://localhost:8081 にアクセス
- 開発中はViteのHMR（ホットリロード）が有効

## よく使うコマンド

### 開発環境の起動（自動化）

**すべてを一度に起動（推奨）：**
```bash
# Docker ComposeでViteも含めて自動起動
./start-dev.sh
```

**ローカル環境でViteを起動：**
```bash
# Viteのみローカル環境で起動（Docker Composeは別途起動）
./start-dev-local.sh
```

### Docker関連
```bash
# コンテナの起動（Viteも含む）
docker-compose up -d

# コンテナの停止
docker-compose down

# コンテナの再起動
docker-compose restart

# ログの確認
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f vite  # Viteのログ

# コンテナ内でコマンド実行
docker-compose exec app php artisan migrate
docker-compose exec app composer install
```

### Laravel関連
```bash
# マイグレーション実行
docker-compose exec app php artisan migrate

# マイグレーション + シーディング
docker-compose exec app php artisan migrate:fresh --seed

# キャッシュクリア
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### フロントエンド関連
```bash
# srcディレクトリに移動
cd src

# 開発サーバー起動（ホットリロード有効）
npm run dev

# 本番用ビルド（srcディレクトリ内で実行）
npm run build
# ビルド結果は src/public/build/ に出力される

# 依存関係の再インストール
rm -rf node_modules package-lock.json
npm install
```

### Node.jsバージョン管理（nvm使用時）
```bash
# Node.jsバージョンの確認
node --version

# Node.js 20を使用
source ~/.nvm/nvm.sh
nvm use 20

# Node.js 20のインストール（未インストールの場合）
nvm install 20
```

## トラブルシューティング

### Viteサーバーが起動しない
- **エラー**: `Vite requires Node.js version 20.19+ or 22.12+`
  - **解決策**: Node.jsをアップグレード
    ```bash
    source ~/.nvm/nvm.sh
    nvm install 20
    nvm use 20
    ```

### Dockerコンテナが起動しない
- **確認**: Docker Desktopが起動しているか確認
- **再起動**: `docker-compose down` → `docker-compose up -d`

### データベース接続エラー
- **確認**: MySQLコンテナが起動しているか確認
  ```bash
  docker-compose ps
  ```
- **再起動**: `docker-compose restart db`

### 画像が表示されない
- **確認**: ストレージリンクが作成されているか
  ```bash
  docker-compose exec app php artisan storage:link
  ```
- **確認**: `src/storage/app/public` に画像ファイルが存在するか

## ディレクトリ構造

```
painter-model-matching/
├── docker/                 # Docker設定ファイル
│   ├── nginx/             # Nginx設定
│   └── php/               # PHP Dockerfile
├── docker-compose.yml     # Docker Compose設定
├── src/                   # Laravelアプリケーション
│   ├── app/              # アプリケーションロジック
│   ├── database/         # マイグレーション・シーダー
│   ├── resources/        # ビュー・CSS・JS
│   ├── routes/          # ルーティング
│   └── storage/         # ストレージ（画像など）
└── README.md            # このファイル
```

## 開発時の注意点

1. **Vite開発サーバーは常に起動しておく**
   - CSSやJavaScriptの変更をリアルタイムで反映するため
   - 停止すると、スタイルが適用されない場合がある

2. **Dockerコンテナとローカル環境の混在**
   - バックエンド（Laravel、MySQL）はDockerコンテナ
   - フロントエンド（Vite）はローカル環境
   - この構成により、ViteのHMRが正常に動作

3. **ポート番号**
   - **8081**: Nginx（アプリケーションアクセス）
   - **5173**: Vite開発サーバー（自動的にLaravelと連携）
   - **3307**: MySQL（直接接続する場合）

## 本番環境へのデプロイ

本番環境では、Viteのビルド済みアセットを使用します：

```bash
# 本番用ビルド
cd src
npm run build

# ビルドされたアセットは public/build/ に出力される
```

## よくある質問（FAQ）

### ViteとNode.js環境について

#### Q: `nvm use 20`の意味は？

**A:** Node.jsのバージョンを20系に切り替えるコマンドです。

- **nvm（Node Version Manager）**: Node.jsのバージョン管理ツール
- **`nvm use 20`**: インストール済みのNode.js 20系（例: 20.20.0）を使用する
- **理由**: このプロジェクトのViteはNode.js 20.19以上または22.12以上が必要なため

**関連コマンド：**
```bash
# 現在のバージョン確認
nvm current

# インストール済みバージョン一覧
nvm ls

# 特定バージョンを使用
nvm use 20

# デフォルトバージョンを設定（再起動後も自動で使用）
nvm alias default 20
```

#### Q: `http://localhost:5173/`が開けない理由は？

**A:** Vite開発サーバーが起動していないためです。

**解決方法：**
```bash
# プロジェクトのsrcディレクトリに移動
cd src

# Node.jsバージョンを設定（nvm使用時）
source ~/.nvm/nvm.sh
nvm use 20

# Vite開発サーバーを起動
npm run dev
```

**注意点：**
- `http://localhost:5173/`はVite開発サーバーのアドレスです
- 通常は`http://localhost:8081/`（Laravelアプリ）にアクセスします
- Viteはバックグラウンドで動作し、CSS/JSの変更を自動反映します
- 直接`http://localhost:5173/`にアクセスする必要は通常ありません

#### Q: なぜ`http://localhost:5173/`を建てたの？

**A:** Vite開発サーバーは、Laravelアプリケーションから自動的に参照されるため必要です。

**役割：**
1. **LaravelとViteの連携**
   - LaravelのBladeテンプレートで`@vite(['resources/css/app.css', 'resources/js/app.js'])`を使用
   - 開発中は、このディレクティブがVite開発サーバー（5173）からアセットを読み込む

2. **ホットリロード（HMR）**
   - CSS/JSの変更を自動反映
   - ブラウザのリロードなしで変更が反映される

3. **開発効率の向上**
   - 変更を即時に確認できる

**動作の流れ：**
```
1. ブラウザで http://localhost:8081/ にアクセス
   ↓
2. LaravelがBladeテンプレートをレンダリング
   ↓
3. @viteディレクティブが http://localhost:5173/ からCSS/JSを読み込む
   ↓
4. ページが表示される（Viteサーバー経由でアセットが配信される）
```

#### Q: Viteを使う際に新しいローカル環境（Node.js）は建てなきゃいけないの？

**A:** 必須ではありません。選択肢は2つあります。

**選択肢A: ローカル環境でNode.jsを使用（現在の構成）**
- **メリット**: HMRが確実に動作、ファイル変更の検知が確実、開発体験が良い
- **デメリット**: ローカルにNode.jsが必要、再起動後に手動で起動が必要

**選択肢B: Dockerコンテナ内でViteを動かす**
- `docker-compose.yml`を修正すれば、Viteもコンテナ内で動かせます
- **メリット**: ローカルにNode.jsが不要、Docker Composeで一括起動可能
- **デメリット**: HMRが不安定になる場合がある（ファイル監視の問題）

**結論**: ローカル環境は必須ではないが、HMRの安定性を重視するならローカルが推奨。

#### Q: Viteの機能と同じ実装をLaravelのみで完結することはできないの？

**A:** 可能ですが、機能は制限されます。

**方法1: 生のCSS/JSファイルを直接読み込む（最もシンプル）**
```blade
<!-- @viteの代わりに -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}"></script>
```
- **メリット**: Node.js不要、シンプル
- **デメリット**: HMRなし、Tailwind CSSのビルドができない、最適化なし、開発効率が下がる

**方法2: 本番ビルドのみ使用（開発時は手動リロード）**
```bash
# 開発時：変更のたびに手動でビルド
npm run build
```
- **メリット**: Node.jsは必要だが、開発サーバーは不要
- **デメリット**: HMRなし、変更のたびにビルドが必要、開発効率が下がる

**方法3: CDNを使用（Tailwind CSSをCDNから読み込む）**
```blade
<script src="https://cdn.tailwindcss.com"></script>
```
- **メリット**: Node.js不要、セットアップが簡単
- **デメリット**: カスタム設定が使えない、ファイルサイズが大きい、本番環境では非推奨

**推奨事項**: 現在の構成（ローカルVite）を推奨。HMRで開発効率が高く、Tailwind CSSのカスタム設定が使え、ビルド最適化が効く。

#### Q: 今回Viteを利用した理由は？

**A:** Laravel Breezeのデフォルト構成として自動的に含まれていたためです。

**経緯：**
1. **Laravel Breezeをインストール**
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   ```

2. **Breezeのインストール時に自動で含まれるもの**
   - Vite設定（`vite.config.js`）
   - `package.json`（Vite関連の依存関係）
   - `@vite`ディレクティブを使ったBladeテンプレート
   - Tailwind CSSの設定

**なぜBreezeがViteを使うのか：**
1. **Laravel 11の標準**: Laravel Mixは非推奨・削除され、Viteが推奨
2. **モダンな開発体験**: 高速なHMR、最適化されたビルド、開発効率の向上
3. **Tailwind CSSとの統合**: BreezeはTailwind CSSを使用し、ViteがTailwindのJITコンパイルを効率的に処理

**まとめ**: 直接Viteを選んだわけではなく、Laravel Breezeを導入した際に自動で含まれた。結果として、モダンなフロントエンド開発環境が整った。

#### Q: 今後の開発にあたって逐一コマンドを押してVite用の環境を作る必要があるの？

**A:** いいえ、自動化できます。

**方法1: Docker Composeで自動起動（推奨）**
```bash
# docker-compose.ymlにViteサービスを追加済み
docker-compose up -d
```
- すべてのサービス（Laravel、MySQL、Nginx、Vite）が一度に起動

**方法2: 起動スクリプトを使用**
```bash
# 完全自動起動（Docker Compose）
./start-dev.sh

# ローカル環境でVite起動
./start-dev-local.sh
```

**方法3: 手動起動（従来の方法）**
```bash
cd src
source ~/.nvm/nvm.sh
nvm use 20
npm run dev
```

**推奨**: `./start-dev.sh`を使用すれば、PC再起動後も1コマンドで開発環境が起動します。

### 環境構成について

#### Q: Dockerコンテナとローカル環境の違いは？

**A:** 以下のように使い分けています。

**Dockerコンテナで動いているもの：**
- Laravelアプリケーション（PHP 8.3-FPM）
- MySQLデータベース（ポート3307）
- Nginx（ポート8081）
- Vite開発サーバー（ポート5173）← Docker Composeで自動起動可能

**ローカル環境で動かすことも可能：**
- Vite開発サーバー（ポート5173）
  - Docker Composeを使わない場合は、ローカル環境で実行
  - ホットリロード（HMR）機能がより安定して動作する場合がある

**理由：**
- バックエンド（Laravel、MySQL）はDockerコンテナで一貫性を保つ
- フロントエンド（Vite）はローカル環境でHMRの安定性を確保（またはDocker Composeで自動起動）

## ライセンス

MIT License

