<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModelAccountController extends Controller
{
    /**
     * 本人確認ページ
     */
    public function identityVerification(): View
    {
        $user = Auth::user();
        $modelProfile = $user->modelProfile;

        return view('model.account.identity-verification', [
            'modelProfile' => $modelProfile,
        ]);
    }

    /**
     * 有料オプション（準備中）
     */
    public function paidOptions(): View
    {
        return view('model.account.coming-soon', [
            'title' => '有料オプション',
            'description' => 'モデルプロフィールの目立つ表示や検索上位表示などの有料オプションは、現在準備中です。',
        ]);
    }

    /**
     * 課金・決済履歴（準備中）
     */
    public function billingHistory(): View
    {
        return view('model.account.coming-soon', [
            'title' => '課金・決済履歴',
            'description' => '利用履歴や決済明細の閲覧機能は、現在準備中です。',
        ]);
    }

    /**
     * カード情報登録（準備中）
     */
    public function paymentMethod(): View
    {
        return view('model.account.coming-soon', [
            'title' => 'カード情報登録',
            'description' => 'クレジットカード等のお支払い方法の登録は、現在準備中です。',
        ]);
    }
}
