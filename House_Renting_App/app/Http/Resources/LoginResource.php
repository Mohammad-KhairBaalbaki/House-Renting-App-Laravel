<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'phone'=>$this->phone,
            'role'=>$this->whenLoaded('roles',$this->roles->pluck('name')->first()),
            'date_of_birth'=>$this->date_of_birth,
            'profile_image'=>$this->whenLoaded('images', ImageResource::make($this->images->where('type','profile_image')->first())),
            'id_image'=>$this->whenLoaded('images', ImageResource::make($this->images->where('type','id_image')->first())),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'access_token'=>$this->access_token
        ];
    }
}
