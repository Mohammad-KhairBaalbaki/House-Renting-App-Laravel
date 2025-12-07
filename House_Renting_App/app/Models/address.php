<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class address extends Model
{
    use HasTranslations;
    protected $fillable = [
        "cities_id",
        "street",
        "flat_number",
        "longitide",
        "latitide"
    ];
    public array $translatable = ['street'];


    public function house()
    {
        return $this->hasOne(House::class);
    }
}
