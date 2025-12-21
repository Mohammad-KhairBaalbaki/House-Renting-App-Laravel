<?php

namespace App\Http\Controllers;

use App\Http\Resources\GovResource;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
     public function index()
    {
        $gov=Governorate::with("cities")->get();
        return $this->success( GovResource::collection($gov)) ;
    }
}
