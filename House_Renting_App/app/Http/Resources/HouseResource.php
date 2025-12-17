<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
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
            'owner' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),

            'title' => $this->title,
            'description' => $this->description,
            'rent_value' => $this->rent_value,
            'rooms' => $this->rooms,
            'space' => $this->space,
            'is_active' => $this->is_active,
            'status' => $this->whenLoaded('status', function () {
                return $this->status->type;
            }),
            'notes' => $this->notes,
            'address' => $this->whenLoaded('address', function () {
                AddressResource::make($this->address);
            }),
            'images' => $this->whenLoaded('images', function () {
                ImageResource::collection($this->images); }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
