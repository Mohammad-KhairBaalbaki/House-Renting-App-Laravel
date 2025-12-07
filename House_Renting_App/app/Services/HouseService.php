<?php

namespace App\Services;

class HouseService
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
        //
    }

    public function store(array $data)
    {

        if(isset($data['new_city']) && $data['city_id']==null)
        {
            
        }
    }

    public function show()
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
