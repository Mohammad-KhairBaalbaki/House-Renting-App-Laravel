<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone' => ['required', 'string', 'exists:users,phone'],
            'password' => ['required', 'string', 'min:8', 'max:20'],
        ];
    }
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a valid text.',
            'phone.exists' => 'No account found with this phone number.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid text.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 20 characters.',
        ];
    }
}
