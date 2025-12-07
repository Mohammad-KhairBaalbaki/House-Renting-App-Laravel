<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HouseController;
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


Route::prefix('houses')->controller(HouseController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
    Route::post('/', 'store')->middleware('role:owner');
    Route::get('/{id}', 'show')->withoutMiddleware('auth:sanctum');;
    Route::put('/{id}', 'update')->middleware('role:owner');
    Route::delete('/{id}', 'destroy')->middleware('role:owner');
  });
Route::middleware('auth:sanctum')->group(function(){


    Route::prefix("address")->middleware("role:owner")->group(function(){
        Route::get("/{address}",[AddressController::class,"index"]);
        Route::post("/",[AddressController::class,"create"]);
        Route::put("/{address}",[AddressController::class,"update"]);
    





});







