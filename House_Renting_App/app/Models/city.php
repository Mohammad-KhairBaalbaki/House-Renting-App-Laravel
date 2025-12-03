<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];
    protected $fillable = ["name", "governorate_id"];
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}
