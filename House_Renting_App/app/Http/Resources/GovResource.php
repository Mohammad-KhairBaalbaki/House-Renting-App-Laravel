<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GovResource extends JsonResource
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
            'name' => $this->name,
           'cities' => $this->whenLoaded('cities', function () {
            return $this->cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                ];
            });
        }),
    ];
}}
