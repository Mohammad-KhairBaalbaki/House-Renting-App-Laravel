<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    protected $fillable = [
        'user_id',
        'house_id',
        'start_date',
        'duration',
        'end_date',
        'status_id'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date:Y-m-d', // "2005-03-13"
            'end_date' => 'date:Y-m-d',
        ];
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }





}
