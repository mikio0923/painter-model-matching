<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModelProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // middlewareで認証チェック済み
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
            'profile_image' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg,gif,webp', 
                'max:10240', // 10MB max
                'dimensions:max_width=5000,max_height=5000' // 最大サイズ制限
            ],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => [
                'image', 
                'mimes:jpeg,png,jpg,gif,webp', 
                'max:10240', // 10MB max
                'dimensions:max_width=5000,max_height=5000' // 最大サイズ制限
            ],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:model_profile_images,id'],
            'main_image_id' => ['nullable', 'integer', 'exists:model_profile_images,id'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:500'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'prefecture' => ['nullable', 'string', 'max:255'],
            'activity_regions' => ['nullable', 'array'],
            'activity_regions.*' => ['string', 'max:255'],
            'height' => ['nullable', 'integer', 'min:1', 'max:300'],
            'bust' => ['nullable', 'integer', 'min:1', 'max:200'],
            'waist' => ['nullable', 'integer', 'min:1', 'max:200'],
            'hip' => ['nullable', 'integer', 'min:1', 'max:200'],
            'shoe_size' => ['nullable', 'string', 'max:10'],
            'clothing_size' => ['nullable', 'string', 'max:10'],
            'model_types' => ['nullable', 'array'],
            'model_types.*' => ['string', 'max:64'],
            'body_type' => ['nullable', 'string', Rule::in(['スリム', '普通', 'グラマー', '細身', 'がっしり'])],
            'occupation' => ['nullable', 'string', 'max:255'],
            'hobbies' => ['nullable', 'string', 'max:500'],
            'avoid_work_types' => ['nullable', 'array'],
            'avoid_work_types.*' => ['string', Rule::in([
                '専属契約', '水着撮影', '衣装チェンジ(着替え)', '商用ストックフォト', '撮影データの販売',
                'スカウト', '露出度の高い衣装', '個室での撮影', '長期に渡る撮影', '撮影データの私的利用(SNS投稿など)',
            ])],
            'hair_type' => ['nullable', 'string', 'in:short,medium,long,semi_long,super_long,other'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'experience' => ['nullable', 'string', 'max:2000'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'sns_links' => ['nullable', 'array'],
            'sns_links.*' => ['url', 'max:255'],
            'style_tags' => ['nullable', 'array'],
            'style_tags.*' => ['string', 'max:50'],
            'pose_ranges' => ['nullable', 'array'],
            'pose_ranges.*' => ['string', 'max:50'],
            'online_available' => ['nullable', 'boolean'],
            'reward_min' => ['nullable', 'integer', 'min:0'],
            'reward_max' => ['nullable', 'integer', 'min:0', 'gte:reward_min'],
            'is_public' => ['nullable', 'boolean'],
            'identity_verified' => ['nullable', 'boolean'],
            'terms_text' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'display_name.required' => '表示名を入力してください。',
            'display_name.max' => '表示名は255文字以内で入力してください。',
            'profile_image.image' => 'プロフィール画像は画像ファイルをアップロードしてください。',
            'profile_image.mimes' => 'プロフィール画像はjpeg、png、jpg、gif、webp形式のみアップロードできます。',
            'profile_image.max' => 'プロフィール画像は10MB以下のファイルをアップロードしてください。',
            'profile_image.dimensions' => 'プロフィール画像のサイズが大きすぎます。最大5000x5000ピクセルまでです。',
            'images.max' => '画像は最大10枚までアップロードできます。',
            'images.*.image' => 'アップロードされたファイルは画像である必要があります。',
            'images.*.mimes' => '画像はjpeg、png、jpg、gif、webp形式のみアップロードできます。',
            'images.*.max' => '画像は10MB以下のファイルをアップロードしてください。',
            'images.*.dimensions' => '画像のサイズが大きすぎます。最大5000x5000ピクセルまでです。',
            'age.integer' => '年齢は数値で入力してください。',
            'age.min' => '年齢は1以上の値を入力してください。',
            'age.max' => '年齢は150以下の値を入力してください。',
            'gender.in' => '性別は「男性」「女性」「その他」から選択してください。',
            'height.integer' => '身長は数値で入力してください。',
            'height.min' => '身長は1以上の値を入力してください。',
            'height.max' => '身長は300以下の値を入力してください。',
            'bust.integer' => 'バストは数値で入力してください。',
            'bust.min' => 'バストは1〜200の範囲で入力してください。',
            'bust.max' => 'バストは1〜200の範囲で入力してください。',
            'waist.integer' => 'ウエストは数値で入力してください。',
            'waist.min' => 'ウエストは1〜200の範囲で入力してください。',
            'waist.max' => 'ウエストは1〜200の範囲で入力してください。',
            'hip.integer' => 'ヒップは数値で入力してください。',
            'hip.min' => 'ヒップは1〜200の範囲で入力してください。',
            'hip.max' => 'ヒップは1〜200の範囲で入力してください。',
            'hair_type.in' => '髪型は選択肢から選んでください。',
            'bio.max' => 'プロフィールは2000文字以内で入力してください。',
            'experience.max' => '経験は2000文字以内で入力してください。',
            'portfolio_url.url' => 'ポートフォリオURLは有効なURLを入力してください。',
            'reward_min.integer' => '最低報酬額は数値で入力してください。',
            'reward_min.min' => '最低報酬額は0以上の値を入力してください。',
            'reward_max.integer' => '最高報酬額は数値で入力してください。',
            'reward_max.min' => '最高報酬額は0以上の値を入力してください。',
            'reward_max.gte' => '最高報酬額は最低報酬額以上の値を入力してください。',
            'terms_text.max' => '取引条件は1000文字以内で入力してください。',
        ];
    }
}

