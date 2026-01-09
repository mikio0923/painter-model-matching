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
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // 5MB max
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:model_profile_images,id'],
            'main_image_id' => ['nullable', 'integer', 'exists:model_profile_images,id'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'prefecture' => ['nullable', 'string', 'max:255'],
            'height' => ['nullable', 'integer', 'min:1', 'max:300'],
            'body_type' => ['nullable', 'string', 'max:255'],
            'hair_type' => ['nullable', 'string', 'in:short,medium,long,other'],
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
}

