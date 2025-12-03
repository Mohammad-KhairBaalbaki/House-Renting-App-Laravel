<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class governorate extends Model
{
     use HasTranslations;

    public array $translatable = ['name'];
    protected $fillable = ["name"];
    public function cities(){
        return $this->hasMany(city::class);
    }
}
