<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
protected $fillable = ['user_id', 'token', 'platform', 'last_seen_at'];

    public function user() { return $this->belongsTo(User::class); }}
