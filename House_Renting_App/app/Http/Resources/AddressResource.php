<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'city' => $this->whenLoaded('city', function () {
                return CityResource::make($this->city);
            }),
            'governorate' => $this->whenLoaded('city.governorate', function () {
                return GovernorateResource::make($this->city->governorate);
            }),
            'street' => $this->street,
            'flat_number' => $this->flat_number,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ];


    }
}
