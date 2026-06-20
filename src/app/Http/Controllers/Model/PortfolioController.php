<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use App\Models\ModelProfileImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    private const MAX_IMAGES = 10;

    public function edit(): View|RedirectResponse
    {
        $modelProfile = Auth::user()->modelProfile;

        if (!$modelProfile) {
            return redirect()->route('model.profile.edit')
                ->with('error', '先にプロフィールを作成してください。');
        }

        $modelProfile->load(['images' => fn($q) => $q->orderBy('display_order')]);

        return view('model.portfolio.edit', compact('modelProfile'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'images'   => ['required', 'array', 'max:' . self::MAX_IMAGES],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        $modelProfile = Auth::user()->modelProfile;

        if (!$modelProfile) {
            return redirect()->route('model.profile.edit')
                ->with('error', '先にプロフィールを作成してください。');
        }

        $current = $modelProfile->images()->count();
        $remaining = self::MAX_IMAGES - $current;

        if ($remaining <= 0) {
            return redirect()->route('model.portfolio.edit')
                ->with('error', 'ポートフォリオは最大' . self::MAX_IMAGES . '枚までです。');
        }

        $order = $modelProfile->images()->max('display_order') ?? 0;

        foreach (array_slice($request->file('images'), 0, $remaining) as $image) {
            $path = $image->store('model_profile_images', 'public');
            ModelProfileImage::create([
                'model_profile_id' => $modelProfile->id,
                'image_path'       => $path,
                'display_order'    => ++$order,
                'is_main'          => false,
            ]);
        }

        return redirect()->route('model.portfolio.edit')
            ->with('success', '画像をアップロードしました。');
    }

    public function updateCaption(Request $request, ModelProfileImage $image): RedirectResponse
    {
        $this->authorizeImage($image);

        $request->validate(['caption' => ['nullable', 'string', 'max:500']]);
        $image->update(['caption' => $request->input('caption')]);

        return redirect()->route('model.portfolio.edit')
            ->with('success', 'キャプションを更新しました。');
    }

    public function setMain(ModelProfileImage $image): RedirectResponse
    {
        $this->authorizeImage($image);

        // 既存のメインを解除
        ModelProfileImage::where('model_profile_id', $image->model_profile_id)
            ->update(['is_main' => false]);

        $image->update(['is_main' => true]);

        // モデルプロフィールのメイン画像パスも更新
        $image->modelProfile->update(['profile_image_path' => $image->image_path]);

        return redirect()->route('model.portfolio.edit')
            ->with('success', 'メイン画像を変更しました。');
    }

    public function destroy(ModelProfileImage $image): RedirectResponse
    {
        $this->authorizeImage($image);

        // ストレージから物理削除
        Storage::disk('public')->delete($image->image_path);

        $image->delete();

        return redirect()->route('model.portfolio.edit')
            ->with('success', '画像を削除しました。');
    }

    private function authorizeImage(ModelProfileImage $image): void
    {
        $modelProfile = Auth::user()->modelProfile;
        abort_unless($modelProfile && $image->model_profile_id === $modelProfile->id, 403);
    }
}
