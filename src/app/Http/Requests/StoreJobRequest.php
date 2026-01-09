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
            'description' => ['required', 'string'],
            'usage_purpose' => ['nullable', 'string', 'max:255'],
            'reward_amount' => ['nullable', 'integer', 'min:0'],
            'reward_unit' => ['nullable', 'string', 'in:per_hour,per_session'],
            'location_type' => ['required', 'string', 'in:online,offline'],
            'prefecture' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'scheduled_date' => ['nullable', 'date'],
            'apply_deadline' => ['nullable', 'date'],
            'model_id' => ['nullable', 'integer', 'exists:model_profiles,id'],
        ];
    }
}

