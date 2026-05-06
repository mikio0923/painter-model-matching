<?php

namespace App\Http\Controllers\Painter;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePainterProfileRequest;
use App\Models\PainterProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PainterProfileEditController extends Controller
{
    /**
     * 画家プロフィール編集画面を表示
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $painterProfile = $user->painterProfile;

        // プロフィールが存在しない場合は作成
        if (!$painterProfile) {
            $painterProfile = PainterProfile::create([
                'user_id' => $user->id,
                'display_name' => $user->name,
            ]);
        }

        return view('painter.profile.edit', [
            'painterProfile' => $painterProfile,
        ]);
    }

    /**
     * 画家プロフィールを更新
     */
    public function update(UpdatePainterProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $painterProfile = $user->painterProfile;

        if (!$painterProfile) {
            $painterProfile = new PainterProfile();
            $painterProfile->user_id = $user->id;
        }

        $validated = $request->validated();

        // カンマ区切り入力 → 配列に変換
        $explode = function (?string $input): array {
            if (!$input) {
                return [];
            }
            return array_values(array_filter(array_map('trim', explode(',', $input))));
        };

        if ($request->has('art_styles_input')) {
            $validated['art_styles'] = $explode($request->input('art_styles_input'));
        }
        if ($request->has('specialties_input')) {
            $validated['specialties'] = $explode($request->input('specialties_input'));
        }
        if ($request->has('activity_regions_input')) {
            $validated['activity_regions'] = $explode($request->input('activity_regions_input'));
        }
        if ($request->has('sns_links_input')) {
            $links = $explode($request->input('sns_links_input'));
            $validated['sns_links'] = array_values(array_filter(
                $links,
                fn ($url) => filter_var($url, FILTER_VALIDATE_URL),
            ));
        }

        // チェックボックス: 未送信なら false にする
        $validated['accepts_offers'] = $request->boolean('accepts_offers');

        $painterProfile->fill($validated);
        $painterProfile->save();

        return redirect()->route('painter.profile.edit')
            ->with('success', 'プロフィールを更新しました');
    }
}
