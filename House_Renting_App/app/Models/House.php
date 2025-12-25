<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
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

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }
}
