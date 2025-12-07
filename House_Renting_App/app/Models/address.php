<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Address extends Model
{
    use HasTranslations;
    protected $fillable = [
        "city_id",
        "street",
        "flat_number",
        "longitude",
        "latitude",
        "street"
        
    ];

    public array $translatable = ['street'];


    public function house()
    {
        return $this->hasOne(House::class);
    }

      public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    

}
