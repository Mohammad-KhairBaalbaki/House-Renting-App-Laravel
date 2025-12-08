<?php

namespace App\Services;

use App\Models\City;
use App\Models\House;
use Illuminate\Support\Facades\Auth;

class HouseService
{
    /**
     * Create a new class instance.
     */

    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index()
    {
        $houses = House::with('address.city.governorate')->where('is_active', true)->where('status_id', 2)->get();
        return $houses;
    }

    public function store(array $data)
    {
        if (!isset($data['city_id'])) {
            $city = City::create([
                'name' => 'UnKnown',
                'governorate_id' => $data['governorate_id'],
            ]);
            $data['city_id'] = $city->id;
        }
        $address = [
            'city_id' => $data['city_id'],
            'street' => $data['street'],
            'flat_number' => $data['flat_number'],
            'longitude' => $data['longitude'],
            'latitude' => $data['latitude'],
        ];

        $address = $this->addressService->create($address);
        unset($data['governorate_id'], $data['city_id'], $data['street'], $data['flat_number'], $data['longitude'], $data['latitude']);

        $data = array_merge($data, [
            'address_id' => $address->id,
            'user_id' => Auth::id(),
            'status_id'=> 1
        ]);

        $house = House::create($data);

        if(isset($data['house_images']))
        {
            foreach ($data['house_images'] as $image) {
                $house->images()->create([
                    'url' => ImageService::uploadImage($image, 'houses'),
                ]);
            }
        }
        $house = $house->fresh();
        return $house->load('user','address','status');

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
