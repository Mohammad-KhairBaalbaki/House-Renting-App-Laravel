<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\changeLanguageController;
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
    //login
    Route::post('/login', 'login');
    //register
    Route::post('/register', 'register');
    //logout
    Route::post('/logout', 'logout')->middleware("auth:sanctum");

});

Route::prefix("profile")->middleware("auth:sanctum")->group(function () {
    //profile
    Route::get("/", [UserController::class, "index"]);
    //update
    Route::post("/", [UserController::class, "update"]);
    //delete
    Route::delete("/", [UserController::class, "delete"]);


});

Route::get("changeLanguage", [changeLanguageController::class, "changeLanguage"]);



Route::middleware('auth:sanctum')->group(function () {

    //houses
    Route::prefix('houses')->controller(HouseController::class)->middleware('auth:sanctum')->group(function () {
        //my houses
        Route::get('/my-houses', 'myHouses')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        //all houses
        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
        //create house
        Route::post('/', 'store')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        //show house
        Route::get('/{id}', 'show')->withoutMiddleware('auth:sanctum');
        //update house
        Route::put('/{house}', 'update')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
        //delete house
        Route::delete('/{id}', 'destroy')->middleware(['role:owner', CheckUserActiveMiddleware::class]);
    });

    //addresses
    Route::prefix("address")->middleware("role:owner")->group(function () {
        //all addresses
        Route::get("/{address}", [AddressController::class, "index"]);
        //create address
        Route::post("/", [AddressController::class, "create"]);
        //update address
        Route::put("/{address}", [AddressController::class, "update"]);

    });

    //governorates
    Route::prefix("governorates")->group(function () {
        //all governorates
        Route::get("/", [GovernorateController::class, "index"]);
    });

    //reservations & rents
    Route::prefix('reservations')->group(function () {
        //all reservations
        Route::get('/', [ReservationController::class, 'index']);

        //check if can rent
        Route::get('/can-rent', [ReservationController::class, 'canRent'])->middleware(['role:renter', CheckUserActiveMiddleware::class]);

        //store reservation
        Route::post('/', [ReservationController::class, 'store'])->middleware(['role:renter', CheckUserActiveMiddleware::class]);

        //all rents of a house
        Route::get('/house/{house}', [ReservationController::class, 'showReservationsOfHouse']);

        //if owner all his reservation
        //if renter all his rents
        Route::get('/my-rents', [ReservationController::class, 'myReservationsAndRents']);

        //cancel reservation
        Route::put('/cancel/{reservation}', [ReservationController::class, 'cancelReservation'])->middleware(['role:renter', CheckUserActiveMiddleware::class]);

        //reject reservation
        Route::put('/reject/{reservation}', [ReservationController::class, 'rejectReservation'])->middleware(['role:owner', CheckUserActiveMiddleware::class]);

        //accept resevation
        Route::put('/accept/{reservation}', [ReservationController::class, 'acceptReservation'])->middleware(['role:owner', CheckUserActiveMiddleware::class]);
    });

    //reviews
    Route::prefix("reviews")->group(function () {
        //create review
        Route::post("/", [ReviewController::class, "store"]);
        //check if can rate
        Route::get("/check-if-can-rate/{house}", [ReviewController::class, "checkIfCanRate"]);
    });

    //favorites
    Route::prefix("favorites")->group(function () {
        //my favorites
        Route::get('my-favorites', [FavoriteController::class, "myFavorites"]);
        //add or remove favourite
        Route::post("/", [FavoriteController::class, "storeOrDelete"]);
    });

    //notification
    Route::prefix('notifications')->group(function () {
        //all notifications
        Route::get('/', [NotificationController::class, 'index']);
        //get unread count
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        //mark as read
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        //mark all as read
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);

    });
    //device token
    Route::post('/device-token', [DeviceTokenController::class, 'store']);

});






