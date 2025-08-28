<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeretaController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TrainApiController;

// Login & Logout
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (hanya bisa diakses kalau sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //=======KERETA=========
    Route::get('/kereta', [KeretaController::class, 'index'])->name('kereta.index');
    Route::get('/kereta/create', [KeretaController::class, 'create'])->name('kereta.create');
    Route::post('/kereta', [KeretaController::class, 'store'])->name('kereta.store');
    Route::get('/kereta/{id}/edit', [KeretaController::class, 'edit'])->name('kereta.edit');
    Route::put('/kereta/{id}', [KeretaController::class, 'update'])->name('kereta.update');
    Route::delete('/kereta/{id}', [KeretaController::class, 'destroy'])->name('kereta.destroy');



    Route::resource('stasiun', StationController::class);

    Route::get('/user', function () {
        return view('admin.user.index');
    })->name('user.index');
});

//=======API========
Route::prefix('api')->group(function () {
    Route::get('/trains', [TrainApiController::class, 'index']);
    Route::get('/trains/{id}', [TrainApiController::class, 'show']);
    Route::post('/trains', [TrainApiController::class, 'store']);
    Route::put('/trains/{id}', [TrainApiController::class, 'update']);
    Route::delete('/trains/{id}', [TrainApiController::class, 'destroy']);
});
