<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdentityVerification;
use App\Models\ModelProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminIdentityVerificationController extends Controller
{
    /**
     * 本人確認申請の一覧
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $query = IdentityVerification::with('user')
            ->orderBy('created_at', 'asc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $verifications = $query->paginate(20)->withQueryString();

        $counts = [
            'pending' => IdentityVerification::where('status', 'pending')->count(),
            'reviewing' => IdentityVerification::where('status', 'reviewing')->count(),
            'approved' => IdentityVerification::where('status', 'approved')->count(),
            'rejected' => IdentityVerification::where('status', 'rejected')->count(),
        ];

        return view('admin.identity-verifications.index', [
            'verifications' => $verifications,
            'currentStatus' => $status,
            'counts' => $counts,
        ]);
    }

    /**
     * 詳細画面
     */
    public function show(IdentityVerification $verification): View
    {
        $verification->load('user.modelProfile', 'reviewer');

        return view('admin.identity-verifications.show', [
            'verification' => $verification,
        ]);
    }

    /**
     * 書類画像を表示（管理者のみ・認証必須）
     */
    public function image(IdentityVerification $verification, string $field): Response
    {
        if (!in_array($field, ['front', 'back', 'selfie'])) {
            abort(404);
        }

        $path = match($field) {
            'front' => $verification->front_image_path,
            'back' => $verification->back_image_path,
            'selfie' => $verification->selfie_image_path,
        };

        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response(Storage::disk('local')->get($path))
            ->header('Content-Type', Storage::disk('local')->mimeType($path))
            ->header('Cache-Control', 'private, no-cache, no-store, must-revalidate');
    }

    /**
     * 承認
     */
    public function approve(IdentityVerification $verification): RedirectResponse
    {
        $verification->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        // ModelProfile に identity_verified フラグを反映
        if ($verification->user->modelProfile) {
            $verification->user->modelProfile->update(['identity_verified' => true]);
        }

        return redirect()->route('admin.identity-verifications.index')
            ->with('success', "ユーザー「{$verification->user->name}」の本人確認を承認しました。");
    }

    /**
     * 差し戻し
     */
    public function reject(Request $request, IdentityVerification $verification): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => '差し戻し理由を入力してください。',
        ]);

        $verification->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // ModelProfile の identity_verified を false に
        if ($verification->user->modelProfile) {
            $verification->user->modelProfile->update(['identity_verified' => false]);
        }

        return redirect()->route('admin.identity-verifications.index')
            ->with('success', "ユーザー「{$verification->user->name}」の本人確認を差し戻しました。");
    }
}
