<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'house_id' => [
                'required',
                'bail',
                Rule::exists('houses', 'id')
                    ->where('status_id', 2)
                    ->where('is_active', true),
            ],
            'start_date' => ['required', 'date'],
            'duration' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'house_id.required' => 'House is required.',
            'house_id.exists' => 'This house is not available (inactive or blocked).',

            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',

            'duration.required' => 'Duration is required.',
            'duration.integer' => 'Duration must be a number.',
        ];
    }
}
