<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Governorate extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];
    protected $fillable = ["name"];
    protected $casts = [
        'name' => 'array',
    ];
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
