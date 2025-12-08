<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    //

    protected $fillable = [
        'user_id',
        'address_id',
        'title',
        'description',
        'rent_value',
        'is_active',
        'status_id',
        'rooms',
        'space',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function images()
    {
        return $this->hasMany(HouseImage::class);
    }
}
