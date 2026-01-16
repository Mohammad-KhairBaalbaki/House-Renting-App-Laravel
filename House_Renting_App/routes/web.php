<?php
// routes/web.php
use App\Http\Controllers\Admin\AdminCityController;
use App\Http\Controllers\Admin\AdminHouseController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

})->middleware('auth');

//login
Route::get('/login', [AdminLoginController::class, 'show'])->name('login.show');
Route::post('/login', [AdminLoginController::class, 'login'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['auth', 'admin'])->group(function () {

        //logout
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        //users index
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        //users edit status
        Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');
        //show user
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');


        //houses index
        Route::get('/houses', [AdminHouseController::class, 'index'])->name('houses.index');
        //show house
        Route::get('/houses/{house}', [AdminHouseController::class, 'show'])->name('houses.show');
        //edit house status
        Route::patch('/houses/{house}/status', [AdminHouseController::class, 'updateStatus'])->name('houses.status');
        //edit house city
        Route::patch('/houses/{house}/city', [AdminHouseController::class, 'updateCity'])
            ->name('houses.city');


        //reservations index
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        //show reservation
        Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');


        // Cities (Admin)
        Route::get('/cities', [AdminCityController::class, 'index'])->name('cities.index');
        //create city view
        Route::get('/cities/create', [AdminCityController::class, 'create'])->name('cities.create');
        //create city
        Route::post('/cities', [AdminCityController::class, 'store'])->name('cities.store');


    });

});
