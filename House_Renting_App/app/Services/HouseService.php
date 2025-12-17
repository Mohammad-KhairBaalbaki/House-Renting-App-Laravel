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

    public function index($request)
    {
        $houses = House::with('address.city.governorate')
            ->where('is_active', true)
            ->where('status_id', 2);

        if ($search = $request->input('search')) {
            $houses->where(function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('rooms', 'like', "%{$search}%")
                    ->orWhere('space', 'like', "%{$search}%")


                    ->orWhereHas('address.city', function ($city) use ($search) {
                        $city->where('name->ar', 'like', "%{$search}%")
                            ->orWhere('name->en', 'like', "%{$search}%");
                    })
                    ->orWhereHas('address.city.governorate', function ($gov) use ($search) {
                        $gov->where('name->ar', 'like', "%{$search}%")
                            ->orWhere('name->en', 'like', "%{$search}%");
                    });
            });
        }
        if ($cityId = $request->input('city_id')) {
            $houses->whereHas('address.city', function ($q) use ($cityId) {
                $q->where('id', $cityId);
            });
        }

        if ($govId = $request->input('governorate_id')) {
            $houses->whereHas('address.city.governorate', function ($q) use ($govId) {
                $q->where('id', $govId);
            });
        }
        if ($Rent = $request->input('rent')) {
            $houses->where('rent_value', $Rent);
        }
        if ($minRent = $request->input('min_rent')) {
            $houses->where('rent_value', '>=', $minRent);
        }
        if ($maxRent = $request->input('max_rent')) {
            $houses->where('rent_value', '<=', $maxRent);
        }

        if ($minSpace = $request->input('min_space')) {
            $houses->where('space', '>=', $minSpace);
        }
        if ($maxSpace = $request->input('max_space')) {
            $houses->where('space', '<=', $maxSpace);
        }

        if ($minRooms = $request->input('min_rooms')) {
            $houses->where('rooms', '>=', $minRooms);
        }
        if ($maxRooms = $request->input('max_rooms')) {
            $houses->where('rooms', '<=', $maxRooms);
        }
        if ($rooms = $request->input('rooms')) {
            $houses->where('rooms', $rooms);
        }

        $allowedSorts = ['rent_value', 'space', 'rooms', 'created_at'];
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $houses->orderBy($sortBy, $sortDir);

        //  $houses = $houses->paginate(5);

        return $houses->paginate(5);
    }

    public function myHouses()
    {
        $houses = House::query()
                ->where('user_id', Auth::id())
                ->with([
                    'address.city.governorate',
                    'status',
                    'images',
                ])
                ->get();
                return $houses;

    }


    public function store(array $data)
    {
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
            'status_id' => 1
        ]);

        $house = House::create($data);

        if (isset($data['house_images'])) {
            foreach ($data['house_images'] as $image) {
                $house->images()->create([
                    'url' => ImageService::uploadImage($image, 'houses'),
                ]);
            }
        }
        $house = $house->fresh();
        return $house->load('user', 'address', 'status');

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
