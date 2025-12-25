<?php

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeOrDelete(array $data){
        $data['user_id'] = Auth::id();
        if(Favorite::where('house_id' , $data['house_id'])->where('user_id' , $data['user_id'])->exists()){
            Favorite::where('house_id' , $data['house_id'])->where('user_id' , $data['user_id'])->delete();
            return '1';
        }
        Favorite::create($data);
        return '2';
    }



}
