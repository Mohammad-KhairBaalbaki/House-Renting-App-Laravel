<?php

// routes/web.php
use App\Http\Controllers\Admin\AdminHouseController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/login', [AdminLoginController::class, 'show'])->name('login.show');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login');
   

    Route::middleware(['auth','admin'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
       Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');
    Route::get('/users/{user}', [AdminUserController::class,'show'])->name('users.show');
     Route::get('/houses', [AdminHouseController::class, 'index'])->name('houses.index');
    Route::get('/houses/{house}', [AdminHouseController::class, 'show'])->name('houses.show');
    Route::patch('/houses/{house}/status', [AdminHouseController::class, 'updateStatus'])->name('houses.status');
Route::patch('/houses/{house}/city', [AdminHouseController::class, 'updateCity'])
  ->name('houses.city');

    });

 });
