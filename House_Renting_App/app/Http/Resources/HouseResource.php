<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'is_favorited' =>$this->whenLoaded('favorites',fn()=>(int) ($this->is_favourite ?? 0)),
            'rating' => $this->whenLoaded(
                'reviews',
                fn() => (float) $this->reviews->avg('rating')
            ),
            'status' => $this->whenLoaded('status', fn() => $this->status->type),
            'notes' => $this->notes,
            'address' => $this->whenLoaded('address', fn() => AddressResource::make($this->address)),
            'images' => $this->whenLoaded('images', fn() => ImageResource::collection($this->images)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
