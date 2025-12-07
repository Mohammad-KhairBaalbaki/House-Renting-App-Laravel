<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Address extends Model
{
    use HasTranslations;
    protected $fillable = [
        "cities_id",
        "street",
        "flat_number",
        "longitide",
        "latitide",
        'street'
        
    ];
      public function city()
    {
        return $this->belongsTo(City::class, 'cities_id');
    }
    
}
