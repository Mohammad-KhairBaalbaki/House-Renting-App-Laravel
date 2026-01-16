<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseRequest;
use App\Http\Requests\UpdateHouseRequest;
use App\Http\Resources\HouseResource;
use App\Models\House;
use App\Services\HouseService;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    //
    protected $houseService;
    public function __construct(HouseService $houseService)
    {
        $this->houseService = $houseService;
    }
    public function index(Request $request)
    {
        $data = $this->houseService->index($request);
        return $this->success(HouseResource::collection($data), 'Houses retrieved successfully', 200);
    }

    public function myHouses()
    {
        $data = $this->houseService->myHouses();
        return $this->success(HouseResource::collection($data), 'Houses retrieved successfully', 200);
    }
    public function store(StoreHouseRequest $request)
    {
        $data = $this->houseService->store($request->validated());
        return $this->success(HouseResource::make($data), 'House created successfully', 201);
    }

    public function show()
    {
        //
    }

    public function update(UpdateHouseRequest $request, House $house)
    {
        $data = $this->houseService->update($house, $request->validated());
        return $this->success(HouseResource::make($data), 'House updated successfully', 200);
    }

    public function destroy()
    {
        //
    }

}
