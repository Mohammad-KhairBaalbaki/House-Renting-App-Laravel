<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'house'=>$this->whenLoaded('house',fn()=>HouseResource::make($this->house)),
            'user'=>$this->whenLoaded('user',fn()=>UserResource::make($this->user)),
            'start_date'=>$this->start_date->format('Y-m-d'),
            'duration'=>$this->duration,
            'end_date'=>$this->end_date->format('Y-m-d'),
            'status'=>$this->whenLoaded('status',fn()=>$this->status->type),
        ];
    }
}
