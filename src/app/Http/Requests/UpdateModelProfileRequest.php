<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'prefecture' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'integer', 'min:1', 'max:300'],
            'body_type' => ['nullable', 'string', 'max:255'],
            'hair_type' => ['nullable', 'string', 'in:short,medium,long,other'],
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
            'hair_type.in' => '髪型は「ショート」「ミディアム」「ロング」「その他」から選択してください。',
            'bio.max' => 'プロフィールは2000文字以内で入力してください。',
            'experience.max' => '経験は2000文字以内で入力してください。',
            'portfolio_url.url' => 'ポートフォリオURLは有効なURLを入力してください。',
            'reward_min.integer' => '最低報酬額は数値で入力してください。',
            'reward_min.min' => '最低報酬額は0以上の値を入力してください。',
            'reward_max.integer' => '最高報酬額は数値で入力してください。',
            'reward_max.min' => '最高報酬額は0以上の値を入力してください。',
            'reward_max.gte' => '最高報酬額は最低報酬額以上の値を入力してください。',
        ];
    }
}

