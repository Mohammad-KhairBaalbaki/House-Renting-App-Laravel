<?php

namespace App\Services;

use App\Models\House;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $userId = $request->user('sanctum')?->id;
        $houses = House::with('address.city.governorate', 'reviews', 'images', 'favorites')
            ->where('is_active', true)
            ->where('status_id', 2)->whereHas('user', function ($q) {
                $q->where('status_id', 2);
            });

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

        if ($userId) {
            $houses->withExists([
                'favorites as is_favourite' => fn($q) => $q->where('user_id', $userId),
            ]);
        }

        $allowedSorts = ['rate', 'rent_value', 'space', 'rooms', 'created_at'];
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        if ($sortBy === 'rate') {
            $houses->withAvg('reviews as avg_rating', 'rating')->having('avg_rating', '>=', 1)
                ->orderByRaw('COALESCE(avg_rating, 0) ' . strtoupper($sortDir));
        } else {
            $houses->orderBy($sortBy, $sortDir);
        }
        return $houses->paginate(10);
    }

    public function myHouses()
    {
        $houses = House::query()
            ->where('user_id', Auth::id())
            ->with([
                'address.city.governorate',
                'status',
                'images',
                'reviews'
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

        ];

        if (isset($data['longitude']) && isset($data['latitude'])) {
            $address['longitude'] = $data['longitude'];
            $address['latitude'] = $data['latitude'];
        } else {
            $address['longitude'] = null;
            $address['latitude'] = null;
        }

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
        $house->load(['user', 'address.city.governorate', 'status', 'favorites']);
        return $house;

    }

    public function show()
    {
        //
    }

    public function update(House $house, array $data)
    {
        $addressData = [];
        if (array_key_exists('city_id', $data)) {
            $addressData['city_id'] = $data['city_id'];
        }
        if (array_key_exists('street', $data)) {
            $addressData['street'] = $data['street'];
        }
        if (array_key_exists('flat_number', $data)) {
            $addressData['flat_number'] = $data['flat_number'];
        }
        if (array_key_exists('longitude', $data)) {
            $addressData['longitude'] = $data['longitude'];
        }
        if (array_key_exists('latitude', $data)) {
            $addressData['latitude'] = $data['latitude'];
        }

        if (!empty($addressData) && $house->address) {
            $this->addressService->update($addressData, $house->address);
        }

        $houseData = $data;
        unset(
            $houseData['governorate_id'],
            $houseData['city_id'],
            $houseData['street'],
            $houseData['flat_number'],
            $houseData['longitude'],
            $houseData['latitude'],
            $houseData['house_images']
        );

        $houseData['status_id'] = 1;
        if (!empty($houseData)) {
            $house->update($houseData);
        }

        if (isset($data['house_images'])) {
            $existingImages = $house->images()->get();
            foreach ($existingImages as $image) {
                Storage::disk('public')->delete($image->url);
            }
            $house->images()->delete();

            foreach ($data['house_images'] as $image) {
                $house->images()->create([
                    'url' => ImageService::uploadImage($image, 'houses'),
                ]);
            }
        }

        return $house->fresh()->load('user', 'address.city.governorate', 'status', 'images');
    }

    public function destroy()
    {
        //
    }
}
