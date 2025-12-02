<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'date_of_birth' => ['required', 'date'],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'max:20'],
            'role'=>['required', 'exists:roles,id']

        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid text.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a valid text.',

            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a valid text.',
            'phone.unique' => 'This phone number is already registered.',

            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid text.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 20 characters.',
        ];
    }


}
