<?php

use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StationController as ApiStationController;
use App\Http\Controllers\Api\TrainController as ApiTrainController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\TripStationController;
use App\Http\Controllers\BookingController as ControllersBookingController;
use App\Http\Controllers\Api\BookingController;

    //===============USER==================
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    //===============AUTH==================
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);

    //===============TRAINS==================
    Route::get('/trains', [ApiTrainController::class, 'index']);
    Route::get('/trains/{id}', [ApiTrainController::class, 'show']);
    Route::post('/trains', [ApiTrainController::class, 'store']);
    Route::put('/trains/{id}', [ApiTrainController::class, 'update']);
    Route::delete('/trains/{id}', [ApiTrainController::class, 'destroy']);

    //===============STATIONS==================
    Route::get('/stations', [ApiStationController::class, 'index']);
    Route::get('/stations/{id}', [ApiStationController::class, 'show']);
    
    //===============TRIPS==================
    Route::get('/trip-stations', [TripStationController::class, 'index']);
    Route::get('/trip-stations/{id}', [TripStationController::class, 'show']);
    Route::post('/trip-stations', [TripStationController::class, 'store']);
    Route::put('/trip-stations/{id}', [TripStationController::class, 'update']);
    Route::delete('/trip-stations/{id}', [TripStationController::class, 'destroy']);
    Route::post('/bookings', [BookingController::class, 'store']);
    
    //===============BOOKING==================
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);

    