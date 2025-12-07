<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHouseRequest extends FormRequest
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
            'governorate_id' => 'required|exists:governorates,id',
            'city_id'        => 'nullable|exists:cities,id',
            'new_city'       => 'string',
            'street'         => 'required|string',
            'flat_number'    => 'required|string',
            'longitude'      => 'nullable|numeric',
            'latitude'       => 'nullable|numeric',
            'title'          => 'nullable|string',
            'description'    => 'nullable|string',
            'rent_value'     => 'required|numeric',
            'rooms'          => 'required|integer',
            'space'          => 'required|numeric',
            'notes'          => 'nullable|string',
        ];
    }
}
