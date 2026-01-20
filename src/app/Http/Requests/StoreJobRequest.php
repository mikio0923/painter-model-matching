<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'usage_purpose' => ['nullable', 'string', 'max:255'],
            'reward_amount' => ['nullable', 'integer', 'min:0', 'max:9999999'],
            'reward_unit' => ['nullable', 'string', 'in:per_hour,per_session'],
            'location_type' => ['required', 'string', 'in:online,offline'],
            'prefecture' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'scheduled_date' => ['nullable', 'date', 'after_or_equal:today'],
            'apply_deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'model_id' => ['nullable', 'integer', 'exists:model_profiles,id'],
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
            'title.required' => 'タイトルを入力してください。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'description.required' => '説明を入力してください。',
            'description.max' => '説明は5000文字以内で入力してください。',
            'reward_amount.integer' => '報酬額は数値で入力してください。',
            'reward_amount.min' => '報酬額は0以上の値を入力してください。',
            'reward_amount.max' => '報酬額は9,999,999以下の値を入力してください。',
            'location_type.required' => '場所タイプを選択してください。',
            'location_type.in' => '場所タイプは「オンライン」または「オフライン」を選択してください。',
            'scheduled_date.after_or_equal' => '予定日は今日以降の日付を入力してください。',
            'apply_deadline.after_or_equal' => '応募締切は今日以降の日付を入力してください。',
        ];
    }
}

