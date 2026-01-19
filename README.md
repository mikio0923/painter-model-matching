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

## ライセンス

MIT License

