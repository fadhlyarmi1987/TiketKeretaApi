<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeretaController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TrainApiController;
use App\Http\Controllers\TripController;

// Login & Logout
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (hanya bisa diakses kalau sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //=======KERETA=========
    Route::resource('kereta', KeretaController::class);
    Route::get('/kereta', [KeretaController::class, 'index'])->name('kereta.index');
    Route::get('/kereta/create', [KeretaController::class, 'create'])->name('kereta.create');
    Route::post('/kereta', [KeretaController::class, 'store'])->name('kereta.store');
    Route::get('/kereta/{id}/edit', [KeretaController::class, 'edit'])->name('kereta.edit');
    Route::put('/kereta/{id}', [KeretaController::class, 'update'])->name('kereta.update');
    Route::delete('/kereta/{id}', [KeretaController::class, 'destroy'])->name('kereta.destroy');

    // ===== TRIPS =====
    Route::resource('trips', TripController::class);
    Route::get('/trips/{trip}/stations/create', [TripController::class, 'create'])->name('tripstations.create');
    Route::post('/trips/{trip}/stations', [TripController::class, 'store'])->name('tripstations.store');
    Route::get('trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
    Route::put('trips/{trip}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');

    // ===== STASIUN =====
    Route::get('/stasiun', [StationController::class, 'index'])->name('stasiun.index');
    Route::get('/stasiun/create', [StationController::class, 'create'])->name('stasiun.create');
    Route::post('/stasiun', [StationController::class, 'store'])->name('stasiun.store');
    Route::get('/stasiun/{station}/edit', [StationController::class, 'edit'])->name('stasiun.edit');
    Route::put('/stasiun/{station}', [StationController::class, 'update'])->name('stasiun.update');
    Route::delete('/stasiun/{station}', [StationController::class, 'destroy'])->name('stasiun.destroy');
    Route::get('/stations/search', [StationController::class, 'searchStations'])->name('stations.search');




    Route::get('/user', function () {
        return view('admin.user.index');
    })->name('user.index');
    //=======API========
    Route::prefix('api')->group(function () {
        Route::get('/trains', [TrainApiController::class, 'index']);
        Route::get('/trains/{id}', [TrainApiController::class, 'show']);
        Route::post('/trains', [TrainApiController::class, 'store']);
        Route::put('/trains/{id}', [TrainApiController::class, 'update']);
        Route::delete('/trains/{id}', [TrainApiController::class, 'destroy']);
    });
});
