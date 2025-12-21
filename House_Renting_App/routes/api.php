<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\changeLanguageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\HouseController;
use App\Http\Middleware\CheckUserActiveMiddleware;
use App\Http\Controllers\UserController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware("auth:sanctum");

});
Route::get("changeLanguage", [changeLanguageController::class, "changeLanguage"]);

Route::prefix("profile")->middleware("auth:sanctum")->group(function () {
    Route::get("/", [UserController::class, "index"]);
    Route::post("/", [UserController::class, "update"]);
    Route::delete("/", [UserController::class, "delete"]);


});
Route::get("changeLanguage", [changeLanguageController::class, "changeLanguage"]);



Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('houses')->controller(HouseController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/my-houses', 'myHouses')->middleware('role:owner');
        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
        Route::post('/', 'store')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        Route::get('/{id}', 'show')->withoutMiddleware('auth:sanctum');
        Route::put('/{house}', 'update')->middleware('role:owner');
        Route::delete('/{id}', 'destroy')->middleware('role:owner');
    });

    Route::prefix("address")->middleware("role:owner")->group(function () {
        Route::get("/{address}", [AddressController::class, "index"]);
        Route::post("/", [AddressController::class, "create"]);
        Route::put("/{address}", [AddressController::class, "update"]);

    });

    Route::prefix("governorates")->group(function () {
        Route::get("/",[GovernorateController::class,"index"]);
        });
});







