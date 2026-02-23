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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ModelProfileEditController extends Controller
{
    /**
     * プロフィールの登録画面（初回登録も編集画面で行うため edit へリダイレクト）
     */
    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('model.profile.edit');
    }

    /**
     * プロフィール登録を保存
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'display_name' => ['required', 'string', 'max:255'],
            'birth_year' => ['required', 'integer', 'min:1900', 'max:' . (int) date('Y')],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'gender' => ['required', 'in:female,male'],
            'prefecture' => ['required', 'string', 'max:255'],
            'activity_regions' => ['nullable', 'array'],
            'activity_regions.*' => ['string', 'max:255'],
            'reference_price' => ['required', 'integer', 'min:1000'],
            'model_types' => ['nullable', 'array'],
            'model_types.*' => ['string', 'max:64'],
            'height' => ['required', 'integer', 'min:1', 'max:300'],
            'shoe_size' => ['required', 'string', Rule::in(self::shoeSizes())],
            'clothing_size' => ['required', 'string', Rule::in(self::clothingSizes())],
            'body_type' => ['required', 'string', 'in:スリム,普通,グラマー,細身,がっしり'],
            'hair_type' => ['required', 'string', 'in:short,medium,long,semi_long,super_long,other'],
            'occupation' => ['required', 'string', 'max:255'],
            'hobbies' => ['required', 'string', 'max:500'],
            'avoid_work_types' => ['nullable', 'array'],
            'avoid_work_types.*' => ['string', Rule::in(self::avoidWorkTypes())],
            'bio' => ['required', 'string', 'max:2000'],
            'main_image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:1536', 'dimensions:max_width=2000,max_height=2000'],
            'sub_images' => ['nullable', 'array'],
            'sub_images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:1536', 'dimensions:max_width=2000,max_height=2000'],
        ];
        $messages = [
            'display_name.required' => 'モデル名を入力してください。',
            'birth_year.required' => '生年月日を選択してください。',
            'birth_month.required' => '生年月日を選択してください。',
            'birth_day.required' => '生年月日を選択してください。',
            'gender.required' => '性別を選択してください。',
            'gender.in' => '性別を選択してください。',
            'prefecture.required' => '所在地を選択してください。',
            'reference_price.required' => '参考価格を入力してください。',
            'reference_price.min' => '参考価格は1000円以上で入力してください。',
            'height.required' => '身長を入力してください。',
            'shoe_size.required' => '靴のサイズを選択してください。',
            'clothing_size.required' => '洋服のサイズを選択してください。',
            'body_type.required' => '体型を選択してください。',
            'hair_type.required' => '髪型を選択してください。',
            'occupation.required' => '職業を入力してください。',
            'hobbies.required' => '趣味を入力してください。',
            'main_image.required' => 'メイン画像を選択してください。',
            'main_image.max' => 'メイン画像は1.5MB以下にしてください。',
            'main_image.dimensions' => 'メイン画像は縦横2000ピクセル以下にしてください。',
            'bio.required' => '自己紹介(コメント)を入力してください。',
            'bio.max' => '自己紹介は2000文字以内で入力してください。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('model.profile.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $user = $request->user();
        $data = $validator->validated();
        $birthdate = \Carbon\Carbon::create(
            $data['birth_year'],
            $data['birth_month'],
            $data['birth_day']
        );

        $modelProfile = $user->modelProfile;
        if (!$modelProfile) {
            $modelProfile = new ModelProfile();
            $modelProfile->user_id = $user->id;
        }

        $modelProfile->display_name = $data['display_name'];
        $modelProfile->birthdate = $birthdate->format('Y-m-d');
        $modelProfile->gender = $data['gender'];
        $modelProfile->prefecture = $data['prefecture'];
        $modelProfile->activity_regions = !empty($data['activity_regions'])
            ? array_values(array_filter($data['activity_regions']))
            : null;
        $modelProfile->reward_min = $data['reference_price'];
        $modelProfile->model_types = !empty($data['model_types'])
            ? array_values(array_filter($data['model_types']))
            : null;
        $modelProfile->height = $data['height'];
        $modelProfile->shoe_size = $data['shoe_size'];
        $modelProfile->clothing_size = $data['clothing_size'];
        $modelProfile->body_type = $data['body_type'];
        $modelProfile->hair_type = $data['hair_type'];
        $modelProfile->occupation = $data['occupation'];
        $modelProfile->hobbies = $data['hobbies'];
        $modelProfile->avoid_work_types = !empty($data['avoid_work_types'])
            ? array_values(array_filter($data['avoid_work_types']))
            : null;
        $modelProfile->bio = $data['bio'];
        $modelProfile->is_public = false;

        $mainPath = $request->file('main_image')->store('model_profiles', 'public');
        $modelProfile->profile_image_path = $mainPath;
        $modelProfile->save();

        $order = 0;
        if ($request->hasFile('sub_images')) {
            foreach ($request->file('sub_images') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $path = $file->store('model_profiles', 'public');
                ModelProfileImage::create([
                    'model_profile_id' => $modelProfile->id,
                    'image_path' => $path,
                    'display_order' => ++$order,
                    'is_main' => false,
                ]);
            }
        }

        return redirect()->route('model.profile.edit')
            ->with('success', 'プロフィールを登録しました。');
    }

    private static function prefectures(): array
    {
        return [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
        ];
    }

    private static function modelTypes(): array
    {
        return ['ファッション', 'コマーシャル', 'カタログ', 'グラビア', 'スチール', 'ランウェイ', 'Web', 'その他'];
    }

    private static function bodyTypes(): array
    {
        return ['スリム', '普通', 'グラマー', '細身', 'がっしり'];
    }

    /** 靴のサイズ（cm） */
    private static function shoeSizes(): array
    {
        $sizes = [];
        for ($i = 20; $i <= 30; $i++) {
            $sizes[] = (string) $i;
            if ($i < 30) {
                $sizes[] = $i . '.5';
            }
        }
        return $sizes;
    }

    /** 洋服のサイズ（号） */
    private static function clothingSizes(): array
    {
        return ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', '3L', '5', '7', '9', '11', '13', '15', '17', '19', '21', '23', '25'];
    }

    /** 避けたい仕事（参考条件）の選択肢 */
    private static function avoidWorkTypes(): array
    {
        return [
            '専属契約',
            '水着撮影',
            '衣装チェンジ(着替え)',
            '商用ストックフォト',
            '撮影データの販売',
            'スカウト',
            '露出度の高い衣装',
            '個室での撮影',
            '長期に渡る撮影',
            '撮影データの私的利用(SNS投稿など)',
        ];
    }

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
        $prefectures = self::prefectures();
        $modelTypes = self::modelTypes();
        $bodyTypes = self::bodyTypes();
        $avoidWorkTypes = self::avoidWorkTypes();
        $shoeSizes = self::shoeSizes();
        $clothingSizes = self::clothingSizes();

        return view('model.profile.edit', [
            'modelProfile' => $modelProfile,
            'prefectures' => $prefectures,
            'modelTypes' => $modelTypes,
            'bodyTypes' => $bodyTypes,
            'avoidWorkTypes' => $avoidWorkTypes,
            'shoeSizes' => $shoeSizes,
            'clothingSizes' => $clothingSizes,
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

        // チェックボックス配列（未送信時は空配列で上書き）
        $validated['activity_regions'] = $request->input('activity_regions', []);
        $validated['avoid_work_types'] = $request->input('avoid_work_types', []);
        $validated['model_types'] = $request->input('model_types', []);

        // 生年月日から年齢を自動計算
        if ($request->filled('birth_year') && $request->filled('birth_month') && $request->filled('birth_day')) {
            $birthdate = \Carbon\Carbon::create(
                $request->birth_year,
                $request->birth_month,
                $request->birth_day
            );
            $validated['age'] = $birthdate->age;
            $validated['birthdate'] = $birthdate->format('Y-m-d');
        } elseif ($request->filled('birthdate')) {
            $birthdate = \Carbon\Carbon::parse($request->birthdate);
            $validated['age'] = $birthdate->age;
            $validated['birthdate'] = $birthdate->format('Y-m-d');
        }

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

        // online_available, is_public, identity_verifiedをbooleanに変換
        $validated['online_available'] = $request->has('online_available');
        $validated['is_public'] = $request->has('is_public');
        $validated['identity_verified'] = $request->has('identity_verified');

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

        // キャプションを更新
        if ($request->has('captions') && is_array($request->captions)) {
            foreach ($request->captions as $imageId => $caption) {
                $image = ModelProfileImage::find($imageId);
                if ($image && $image->model_profile_id === $modelProfile->id) {
                    $image->update(['caption' => $caption ?: null]);
                }
            }
        }

        return redirect()->route('model.profile.edit')
            ->with('success', 'プロフィールを更新しました');
    }
}
