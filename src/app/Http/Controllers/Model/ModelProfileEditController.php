<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateModelProfileRequest;
use App\Models\ModelProfile;
use App\Models\ModelProfileImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ModelProfileEditController extends Controller
{
    /**
     * モデルプロフィール編集画面を表示
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $modelProfile = $user->modelProfile;

        // プロフィールが存在しない場合は作成
        if (!$modelProfile) {
            $modelProfile = ModelProfile::create([
                'user_id' => $user->id,
                'display_name' => $user->name,
                'is_public' => false,
            ]);
        }

        $modelProfile->load('images');

        return view('model.profile.edit', [
            'modelProfile' => $modelProfile,
        ]);
    }

    /**
     * モデルプロフィールを更新
     */
    public function update(UpdateModelProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $modelProfile = $user->modelProfile;

        if (!$modelProfile) {
            $modelProfile = new ModelProfile();
            $modelProfile->user_id = $user->id;
        }

        $validated = $request->validated();

        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            // 古い画像を削除
            if ($modelProfile->profile_image_path) {
                Storage::disk('public')->delete($modelProfile->profile_image_path);
            }

            // 新しい画像を保存
            $imagePath = $request->file('profile_image')->store('model_profiles', 'public');
            $validated['profile_image_path'] = $imagePath;
        }

        // style_tagsとpose_rangesをJSON形式で保存
        if ($request->has('style_tags_input')) {
            $tags = array_filter(array_map('trim', explode(',', $request->input('style_tags_input'))));
            $validated['style_tags'] = array_values($tags);
        } elseif (isset($validated['style_tags'])) {
            $validated['style_tags'] = array_values(array_filter($validated['style_tags']));
        }

        if ($request->has('pose_ranges_input')) {
            $ranges = array_filter(array_map('trim', explode(',', $request->input('pose_ranges_input'))));
            $validated['pose_ranges'] = array_values($ranges);
        } elseif (isset($validated['pose_ranges'])) {
            $validated['pose_ranges'] = array_values(array_filter($validated['pose_ranges']));
        }

        // SNSリンクを配列形式で保存
        if ($request->has('sns_links_input')) {
            $links = array_filter(array_map('trim', explode(',', $request->input('sns_links_input'))));
            $validated['sns_links'] = array_values($links);
        } elseif (isset($validated['sns_links'])) {
            $validated['sns_links'] = array_values(array_filter($validated['sns_links']));
        }

        // online_availableとis_publicをbooleanに変換
        $validated['online_available'] = $request->has('online_available');
        $validated['is_public'] = $request->has('is_public');

        // profile_imageキーを削除（profile_image_pathに変換済み）
        unset($validated['profile_image']);

        $modelProfile->fill($validated);
        $modelProfile->save();

        // 削除する画像を削除
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ModelProfileImage::find($imageId);
                if ($image && $image->model_profile_id === $modelProfile->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // 新しい画像をアップロード
        if ($request->hasFile('images')) {
            $maxOrder = $modelProfile->images()->max('display_order') ?? 0;
            foreach ($request->file('images') as $file) {
                $imagePath = $file->store('model_profiles', 'public');
                ModelProfileImage::create([
                    'model_profile_id' => $modelProfile->id,
                    'image_path' => $imagePath,
                    'display_order' => ++$maxOrder,
                    'is_main' => false,
                ]);
            }
        }

        // メイン画像を設定
        if ($request->has('main_image_id')) {
            // 既存のメイン画像を解除
            $modelProfile->images()->update(['is_main' => false]);
            // 新しいメイン画像を設定
            $mainImage = ModelProfileImage::find($request->main_image_id);
            if ($mainImage && $mainImage->model_profile_id === $modelProfile->id) {
                $mainImage->update(['is_main' => true]);
            }
        }

        return redirect()->route('model.profile.edit')
            ->with('success', 'プロフィールを更新しました');
    }
}
