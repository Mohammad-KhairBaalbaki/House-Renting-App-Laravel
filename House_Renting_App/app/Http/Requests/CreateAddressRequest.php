<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
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
       "cities_id"=>"required|exists:cities,id|",
        "street"=>"required|string",
        "flat_number"=>"required|string",
        "longitide"=>"integer|nullable",
        "latitide"=>"integer|nullable",
        'street'=>"required|string"
        ];
    }
}
