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
            'city'        => $this->whenLoaded('city', function () {
                return $this->city->name;
            }),

            'governorate' => $this->whenLoaded('city', function () {
                return $this->city->governorate->name;
            }),

            'street'      => $this->street,
            'flat_number' => $this->flat_number,
            'longitide'   => $this->longitide,
            'latitide'    => $this->latitide,
        ];

        
    }
}
