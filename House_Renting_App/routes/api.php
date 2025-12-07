<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});
Route::middleware('auth:sanctum')->group(function(){


    Route::prefix("address")->middleware("role:owner")->group(function(){
        Route::get("/{address}",[AddressController::class,"index"]);
        Route::post("/",[AddressController::class,"create"]);
        Route::put("/{address}",[AddressController::class,"update"]);

    });





});





Route::get('test3',function(){
    return false;
});


Route::get('test1',function(){
    return true;
});

