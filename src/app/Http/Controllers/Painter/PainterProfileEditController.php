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

        // art_stylesを配列形式で保存
        if ($request->has('art_styles_input')) {
            $styles = array_filter(array_map('trim', explode(',', $request->input('art_styles_input'))));
            $validated['art_styles'] = array_values($styles);
        } elseif (isset($validated['art_styles'])) {
            $validated['art_styles'] = array_values(array_filter($validated['art_styles']));
        }

        $painterProfile->fill($validated);
        $painterProfile->save();

        return redirect()->route('painter.profile.edit')
            ->with('success', 'プロフィールを更新しました');
    }
}
