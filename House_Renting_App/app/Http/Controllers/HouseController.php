<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHouseRequest;
use App\Http\Resources\HouseResource;
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
    public function index()
    {
        //
    }

    public function store(StoreHouseRequest $request)
    {
        $data = $this->houseService->store($request->validated());
        return $this->success(HouseResource::make($data),'House created successfully',201);
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
