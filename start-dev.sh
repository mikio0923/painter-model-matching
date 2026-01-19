#!/bin/bash

# 開発環境起動スクリプト
# 使用方法: ./start-dev.sh

set -e

echo "🚀 開発環境を起動しています..."

# プロジェクトルートに移動
cd "$(dirname "$0")"

# Dockerコンテナの起動
echo "📦 Dockerコンテナを起動中..."
docker-compose up -d

# コンテナの状態確認
echo "⏳ コンテナの起動を待機中..."
sleep 5

# コンテナの状態を表示
docker-compose ps

echo ""
echo "✅ 開発環境が起動しました！"
echo ""
echo "📝 アクセス先:"
echo "   - アプリケーション: http://localhost:8081"
echo "   - Vite開発サーバー: http://localhost:5173"
echo ""
echo "🛑 停止する場合: docker-compose down"

