<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
     use HasTranslations;

    public array $translatable = ['name'];
    protected $fillable = ["name","governorate_id"];
       protected $casts = [
        'name' => 'array',
    ];
public function governorate(){
        return $this->belongsTo(Governorate::class);
    }
       public function addresses()
    {
        return $this->hasMany(Address::class, 'cities_id');
    }
}
