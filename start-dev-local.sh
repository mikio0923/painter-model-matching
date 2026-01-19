#!/bin/bash

# ローカル環境でViteを起動するスクリプト（Docker Composeを使わない場合）
# 使用方法: ./start-dev-local.sh

set -e

echo "🚀 ローカル開発環境を起動しています..."

# プロジェクトルートに移動
cd "$(dirname "$0")"

# nvmが利用可能な場合、Node.jsバージョンを設定
if [ -s "$HOME/.nvm/nvm.sh" ]; then
    echo "📦 Node.js環境を設定中..."
    source "$HOME/.nvm/nvm.sh"
    nvm use 20 2>/dev/null || nvm install 20
fi

# srcディレクトリに移動
cd src

# 依存関係の確認
if [ ! -d "node_modules" ]; then
    echo "📦 npmパッケージをインストール中..."
    npm install
fi

# Vite開発サーバーを起動
echo "🎨 Vite開発サーバーを起動中..."
echo "   アクセス先: http://localhost:5173"
echo ""
echo "🛑 停止する場合: Ctrl+C"
echo ""

npm run dev

