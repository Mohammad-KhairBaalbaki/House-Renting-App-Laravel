<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\House;
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

    public function storeOrDelete(array $data)
    {
        $data['user_id'] = Auth::id();
        if (Favorite::where('house_id', $data['house_id'])->where('user_id', $data['user_id'])->exists()) {
            Favorite::where('house_id', $data['house_id'])->where('user_id', $data['user_id'])->delete();
            return '1';
        }
        Favorite::create($data);
        return '2';
    }

    public function myFavorites()
    {
        $houses = House::where('status_id', 2)->whereHas('favorites', function ($q) {
            $q->where('user_id', Auth::id());
        })->get();


        return $houses->load('address.city.governorate', 'status', 'images');
    }



}
