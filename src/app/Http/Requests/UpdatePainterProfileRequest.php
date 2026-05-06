<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePainterProfileRequest extends FormRequest
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
            'display_name'    => ['required', 'string', 'max:255'],
            'gender'          => ['nullable', 'string', 'in:male,female,other'],
            'bio'             => ['nullable', 'string', 'max:2000'],
            'experience'      => ['nullable', 'string', 'max:2000'],
            'years_active'    => ['nullable', 'integer', 'min:0', 'max:80'],
            'accepts_offers'  => ['nullable', 'boolean'],
            'art_styles'      => ['nullable', 'array'],
            'art_styles.*'    => ['string', 'max:50'],
            'specialties'     => ['nullable', 'array'],
            'specialties.*'   => ['string', 'max:50'],
            'portfolio_url'   => ['nullable', 'url', 'max:255'],
            'sns_links'       => ['nullable', 'array'],
            'sns_links.*'     => ['url', 'max:255'],
            'prefecture'      => ['nullable', 'string', 'max:255'],
            'activity_regions'=> ['nullable', 'array'],
            'activity_regions.*' => ['string', 'max:255'],
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
            'portfolio_url.url' => 'ポートフォリオURLは有効なURLを入力してください。',
            'portfolio_url.max' => 'ポートフォリオURLは255文字以内で入力してください。',
            'prefecture.max' => '都道府県は255文字以内で入力してください。',
        ];
    }
}
