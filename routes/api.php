<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StationController as ApiStationController;
use App\Http\Controllers\Api\TrainController as ApiTrainController;
use App\Http\Controllers\Api\TripStationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\Api\PesanController;

// ===== PUBLIC ROUTES =====
// Auth
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// ===== PROTECTED ROUTES (harus login) =====
// Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    // Trains
    Route::get('/trains', [ApiTrainController::class, 'index']);
    Route::get('/trains/{id}', [ApiTrainController::class, 'show']);
    Route::post('/trains', [ApiTrainController::class, 'store']);
    Route::put('/trains/{id}', [ApiTrainController::class, 'update']);
    Route::delete('/trains/{id}', [ApiTrainController::class, 'destroy']);

    Route::get('/trains/{trainId}/seats', [SeatController::class, 'getSeatsByTrain']);
    // Stations
    Route::get('/stations', [ApiStationController::class, 'index']);
    Route::get('/stations/{id}', [ApiStationController::class, 'show']);

    // Trip Stations
    Route::get('/trip-stations', [TripStationController::class, 'index']);
    Route::get('/trip-stations/{id}', [TripStationController::class, 'show']);
    Route::post('/trip-stations', [TripStationController::class, 'store']);
    Route::put('/trip-stations/{id}', [TripStationController::class, 'update']);
    Route::delete('/trip-stations/{id}', [TripStationController::class, 'destroy']);
    Route::get('/trip/search', [TripStationController::class, 'search']);
    
    // Bookings
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::get('/trips/search', [BookingController::class, 'search']);
    Route::post('/trips/search', [BookingController::class, 'search']);



    Route::post('/pesan', [PesanController::class, 'store']);
    Route::patch('/bookings/user/{userId}/status', [PesanController::class, 'updateStatusByUser']);

// });
