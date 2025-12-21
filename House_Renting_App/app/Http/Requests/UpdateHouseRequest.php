<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateHouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::id()===$this->route('house')->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city_id'        => 'sometimes|exists:cities,id',
            'street'         => 'sometimes|string',
            'flat_number'    => 'sometimes|string',
            'longitude'      => 'sometimes|numeric|nullable',
            'latitude'       => 'sometimes|numeric|nullable',
            'title'          => 'sometimes|nullable|string',
            'description'    => 'sometimes|nullable|string',
            'rent_value'     => 'sometimes|numeric',
            'rooms'          => 'sometimes|integer',
            'space'          => 'sometimes|numeric',
            'notes'          => 'sometimes|nullable|string',
            'is_active'      => 'sometimes|boolean',
            'house_images'   => 'sometimes|array',
            'house_images.*' => 'image|mimes:jpeg,png,jpg,svg',
        ];
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'You are not allowed to perform this action.',
            'data' => null,
        ], 403));
    }
}
