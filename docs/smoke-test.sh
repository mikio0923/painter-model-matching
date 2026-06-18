#!/bin/bash
# Palette HTTP スモークテスト
# 使い方:
#   bash docs/smoke-test.sh                       # ローカル (Docker) 向け
#   bash docs/smoke-test.sh http://220.158.26.223 # VPS 向け

BASE="${1:-http://localhost:8081}"
echo "🧪 Palette スモークテスト"
echo "📍 ベースURL: $BASE"
echo "═══════════════════════════════════════════"

PASS=0
FAIL=0
FAIL_LIST=()

check() {
    local path="$1"
    local desc="$2"
    local expected="${3:-200 302 301}"

    local code
    code=$(curl -s -o /dev/null -w "%{http_code}" -L --max-redirs 0 "${BASE}${path}")

    local ok=0
    for e in $expected; do
        if [ "$code" = "$e" ]; then
            ok=1
            break
        fi
    done

    if [ "$ok" = "1" ]; then
        printf "  ✓ %3d  %-50s %s\n" "$code" "$path" "$desc"
        PASS=$((PASS+1))
    else
        printf "  ✗ %3d  %-50s %s\n" "$code" "$path" "$desc"
        FAIL=$((FAIL+1))
        FAIL_LIST+=("$code $path")
    fi
}

echo ""
echo "── 公開ページ（200 期待）──"
check "/" "トップページ"
check "/about" "サービスについて"
check "/faq" "Q&A"
check "/guideline" "利用ガイドライン"
check "/privacy" "プライバシーポリシー"
check "/terms" "利用規約"
check "/guide/model" "モデルガイド"
check "/guide/painter" "画家ガイド"
check "/contact" "お問い合わせ"

echo ""
echo "── 一覧ページ（200 期待）──"
check "/models" "モデル一覧"
check "/jobs" "依頼一覧"
check "/information" "お知らせ一覧"

echo ""
echo "── 認証関連（200 期待）──"
check "/login-register" "ログイン/登録"
check "/register" "新規登録"
check "/forgot-password" "パスワードリセット"

echo ""
echo "── 管理画面ログイン（200 期待）──"
check "/admin/login" "管理画面ログイン"

echo ""
echo "── 認証必須ページ（未ログインなら 302 リダイレクト期待）──"
check "/mypage" "マイページ" "302"
check "/messages" "メッセージ" "302"
check "/favorites" "お気に入り" "302"
check "/notifications" "通知" "302"
check "/admin/dashboard" "管理ダッシュボード" "302"

echo ""
echo "── ロール限定ページ（未ログインで 302 期待）──"
check "/model/profile/edit" "モデルプロフィール編集" "302"
check "/model/applications" "エントリー履歴" "302"
check "/painter/jobs" "依頼管理" "302"
check "/painter/jobs/create" "依頼作成" "302"

echo ""
echo "── 静的アセット（200 期待）──"
check "/build/manifest.json" "Vite manifest"
check "/robots.txt" "robots.txt"
check "/favicon.ico" "favicon" "200 404"

echo ""
echo "── 存在しないページ（404 期待）──"
check "/does-not-exist" "404 ページ" "404"

echo ""
echo "═══════════════════════════════════════════"
TOTAL=$((PASS+FAIL))
echo "📊 結果: $PASS 成功 / $FAIL 失敗 / 合計 $TOTAL"
if [ "$FAIL" -gt 0 ]; then
    echo ""
    echo "❌ 失敗一覧:"
    for f in "${FAIL_LIST[@]}"; do
        echo "  $f"
    done
    exit 1
fi
echo "✅ 全て OK"
