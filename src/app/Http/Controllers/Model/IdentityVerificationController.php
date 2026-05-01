<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\IdentityVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IdentityVerificationController extends Controller
{
    /**
     * 本人確認画面（申請フォーム + 状態表示）
     */
    public function show(): View
    {
        $user = Auth::user();
        $latest = $user->latestIdentityVerification();

        return view('model.account.identity-verification', [
            'latest' => $latest,
            'documentTypes' => IdentityVerification::DOCUMENT_TYPES,
        ]);
    }

    /**
     * 本人確認書類を提出
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // 既に承認済み or 審査中のものがあるかチェック
        $existing = $user->identityVerifications()
            ->whereIn('status', ['pending', 'reviewing', 'approved'])
            ->first();

        if ($existing) {
            $message = $existing->status === 'approved'
                ? '既に本人確認は承認済みです。'
                : '審査中の申請があります。結果が出るまでお待ちください。';

            return redirect()->route('model.identity-verification')
                ->with('error', $message);
        }

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:' . implode(',', array_keys(IdentityVerification::DOCUMENT_TYPES))],
            'front_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'back_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'selfie_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ], [
            'front_image.required' => '本人確認書類の表面画像を添付してください。',
            'front_image.image' => '画像ファイルをアップロードしてください。',
            'front_image.max' => 'ファイルサイズは10MB以下にしてください。',
        ]);

        // 非公開ストレージに保存
        $frontPath = $request->file('front_image')->store("identity/{$user->id}", 'local');
        $backPath = $request->hasFile('back_image')
            ? $request->file('back_image')->store("identity/{$user->id}", 'local')
            : null;
        $selfiePath = $request->hasFile('selfie_image')
            ? $request->file('selfie_image')->store("identity/{$user->id}", 'local')
            : null;

        IdentityVerification::create([
            'user_id' => $user->id,
            'document_type' => $validated['document_type'],
            'front_image_path' => $frontPath,
            'back_image_path' => $backPath,
            'selfie_image_path' => $selfiePath,
            'status' => 'pending',
        ]);

        return redirect()->route('model.identity-verification')
            ->with('success', '本人確認書類を提出しました。審査結果をお待ちください。');
    }
}
