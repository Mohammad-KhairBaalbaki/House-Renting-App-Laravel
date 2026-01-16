<?php

namespace App\Services;

use App\Models\House;
use App\Models\review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {

    }
    public function store(array $data)
    {
        $user = Auth::user();
        $house = House::find($data['house_id']);
        if ($this->checkIfReserved($house, $user)) {
            if ($this->checkIfReviewdOnce($house, $user)) {
                return '1';
            }
            $data['user_id'] = $user->id;
            return Review::create($data);
        }
        return '2';
    }

    public function checkIfReserved(House $house, User $user)
    {
        return $house->reservations()->where('user_id', $user->id)->where('status_id', 6)->exists() && $house->user_id != $user->id;
    }

    public function checkIfReviewdOnce(House $house, User $user)
    {
        return ($house->reviews()->where('user_id', $user->id)->exists());
    }
}
