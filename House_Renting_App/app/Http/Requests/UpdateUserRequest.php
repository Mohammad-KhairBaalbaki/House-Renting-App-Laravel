<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => ['sometimes', 'string'],
            'last_name' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string', 'unique:users,phone,'.$this->user()->id],
            'date_of_birth' => ['sometimes', 'date'],
            'password' => ['sometimes', 'string', 'confirmed', 'min:8', 'max:20'],
            'profile_image'=>[ "sometimes",'file', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ];
    }
}
