<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\changeLanguageController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DeviceTokenController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\CheckUserActiveMiddleware;
use App\Http\Controllers\UserController;
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

Route::prefix("profile")->middleware("auth:sanctum")->group(function () {
    Route::get("/", [UserController::class, "index"]);
    Route::post("/", [UserController::class, "update"]);
    Route::delete("/", [UserController::class, "delete"]);


});
Route::get("changeLanguage", [changeLanguageController::class, "changeLanguage"]);



Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('houses')->controller(HouseController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('/my-houses', 'myHouses')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
        Route::post('/', 'store')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        Route::get('/{id}', 'show')->withoutMiddleware('auth:sanctum');
        Route::put('/{house}', 'update')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        Route::delete('/{id}', 'destroy')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
    });

    Route::prefix("address")->middleware("role:owner")->group(function () {
        Route::get("/{address}", [AddressController::class, "index"]);
        Route::post("/", [AddressController::class, "create"]);
        Route::put("/{address}", [AddressController::class, "update"]);

    });

    Route::prefix("governorates")->group(function () {
        Route::get("/",[GovernorateController::class,"index"]);
        });

    Route::prefix('reservations')->group(function ()  {
        //all reservations
        Route::get('/',[ReservationController::class,'index']);


        //check if can rent
        Route::get('/can-rent',[ReservationController::class,'canRent'])->middleware(['role:renter', CheckUserActiveMiddleware::class]);

        //store reservation
        Route::post('/',[ReservationController::class,'store'])->middleware(['role:renter', CheckUserActiveMiddleware::class]);

        //all rents of a house
        Route::get('/house/{house}',[ReservationController::class,'showReservationsOfHouse']);

        //if owner all his reservation
        //if renter all his rents
        Route::get('/my-rents',[ReservationController::class,'myReservationsAndRents']);

        //cancel reservation
        Route::put('/cancel/{reservation}',[ReservationController::class,'cancelReservation'])->middleware(['role:renter', CheckUserActiveMiddleware::class]);

        //reject reservation
        Route::put('/reject/{reservation}',[ReservationController::class,'rejectReservation'])->middleware(['role:owner', CheckUserActiveMiddleware::class]);

        //accept resevation
        Route::put('/accept/{reservation}',[ReservationController::class,'acceptReservation'])->middleware(['role:owner', CheckUserActiveMiddleware::class]);
    });

    Route::prefix("reviews")->group(function () {
        Route::post("/",[ReviewController::class,"store"]);
        Route::get("/check-if-can-rate/{house}",[ReviewController::class,"checkIfCanRate"]);
    });

    Route::prefix("favorites")->group(function () {

        Route::get('my-favorites',[FavoriteController::class,"myFavorites"]);
        Route::post("/",[FavoriteController::class,"storeOrDelete"]);
    });

    Route::prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);

});
    Route::post('/device-token', [DeviceTokenController::class, 'store']);

});






