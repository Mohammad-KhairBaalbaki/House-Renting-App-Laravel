<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

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
            "current_password"=>['required',"string"],
            'first_name' => ['sometimes', 'string'],
            'last_name' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string', 'unique:users,phone,'.$this->user()->id],
            'date_of_birth' => ['sometimes', 'date'],
            'new_password' => ['sometimes', 'string', 'confirmed', 'min:8', 'max:20'],
            'profile_image'=>[ "sometimes",'file', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ];
    }
public function withValidator($validator)
{
    $validator->after(function ($validator) {

        if (!$this->filled('current_password')) {
            return;
        }

        if (!Hash::check($this->input('current_password'), $this->user()->password)) {
            $validator->errors()->add('current_password', 'Current password is incorrect.');
        }
        $excluded = ['current_password', 'new_password_confirmation'];

        $keys = collect($this->all())->keys()->diff($excluded);

        if ($keys->isEmpty()) {
            $validator->errors()->add('data', 'No data provided to update.');
        
      }
    });
}
}