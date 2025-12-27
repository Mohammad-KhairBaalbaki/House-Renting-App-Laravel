<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\HouseResource;
use App\Models\House;
use App\Services\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    //
    protected $favoriteService;
    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function storeOrDelete(FavoriteRequest $request){
        $data= $this->favoriteService->storeOrDelete($request->validated());
        if($data ==='1'){
            return $this->success(false,'you deleted the favorite from this house',400);
        }
        else{
            return $this->success(true,'you created a favorite for this house',201);
        }
    }

    public function myFavorites(){
        $data = $this->favoriteService->myFavorites();
        return $this->success(HouseResource::collection($data),'Houses favorrited retrieved successfully',200);
    }
}
