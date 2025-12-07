<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\address;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $addressService;
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }
    public function index(Address $address)

    {
          $address= $this->addressService->index($address);
        return new AddressResource($address);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateAddressRequest $request)
    {
        $address= $this->addressService->create($request->validated());
        return $this->success(new AddressResource($address));
    }

      public function update(UpdateAddressRequest $request,Address $address)
    {
        $address= $this->addressService->update($request->validated(),$address);
        return $this->success(new AddressResource($address));
    }
    
}
