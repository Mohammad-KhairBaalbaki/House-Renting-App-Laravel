<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

abstract class Controller
{
    //
    public function success($data,$message='Success',$statusCode=200){
        return response()->json([
            'message'=>$message,
            'data'=>$data
        ],$statusCode);
    }
  

}
