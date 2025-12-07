<?php

namespace App\Services;

use App\Models\Address;

class AddressService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }
    public function index($address){
        $address->get();
        return $address->load("city.governorate");
    }
    public function create($request){
        $address= Address::create($request);
        return $address->load("city.governorate");
    }
    public function update($request,$address){
        $address->update($request);
        return $address->load("city.governorate");

    }
}
