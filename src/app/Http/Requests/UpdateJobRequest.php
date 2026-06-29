<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
        // 仕様: 「アクセス補足」以外は全て必須
        return [
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string', 'max:5000'],
            'usage_purpose'      => ['required', 'string', 'max:255'],
            'reward_amount'      => ['required', 'integer', 'min:0', 'max:9999999'],
            'reward_unit'        => ['required', 'string', 'in:per_hour,per_session'],
            'transportation_fee' => ['required', 'string', 'max:255'],
            'costume_provided'   => ['required', 'string', 'max:255'],
            'target'             => ['required', 'string', 'max:255'],
            'recruitment_number' => ['nullable', 'integer', 'min:1', 'max:1'],
            'location_type'      => ['required', 'string', 'in:online,offline'],
            'prefecture'         => ['required', 'string', 'max:50'],
            'city'               => ['required', 'string', 'max:100'],
            'address'            => ['required', 'string', 'max:255'],
            // 唯一の任意項目: アクセス補足
            'access'             => ['nullable', 'string', 'max:5000'],
            'scheduled_date'     => ['required', 'date', 'after_or_equal:apply_deadline'],
            'apply_deadline'     => ['required', 'date'],
            'status'             => ['nullable', 'string', 'in:open,closed,done'],
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
            'title.required'              => 'タイトルを入力してください。',
            'title.max'                   => 'タイトルは255文字以内で入力してください。',
            'description.required'        => '説明を入力してください。',
            'description.max'             => '説明は5000文字以内で入力してください。',
            'usage_purpose.required'      => '用途を入力してください。',
            'reward_amount.required'      => '報酬額を入力してください。',
            'reward_amount.integer'       => '報酬額は数値で入力してください。',
            'reward_amount.min'           => '報酬額は0以上の値を入力してください。',
            'reward_amount.max'           => '報酬額は9,999,999以下の値を入力してください。',
            'reward_unit.required'        => '報酬の単位を選択してください。',
            'transportation_fee.required' => '交通費の有無を入力してください。',
            'costume_provided.required'   => '衣装提供の有無を入力してください。',
            'target.required'             => '募集対象を入力してください。',
            'location_type.required'      => '場所タイプを選択してください。',
            'location_type.in'            => '場所タイプは「オンライン」または「オフライン」を選択してください。',
            'prefecture.required'         => '都道府県を入力してください。',
            'city.required'               => '市区町村を入力してください。',
            'address.required'            => '住所を入力してください。',
            'scheduled_date.required'     => '撮影日を入力してください。',
            'scheduled_date.after_or_equal' => '撮影日は応募締切以降の日付を入力してください。',
            'apply_deadline.required'     => '応募締切を入力してください。',
        ];
    }
}

