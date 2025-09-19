<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeretaController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TripController;

// Login & Logout
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (hanya bisa diakses kalau sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //================KERETA==============
    Route::resource('kereta', KeretaController::class);
    Route::get('/kereta', [KeretaController::class, 'index'])->name('kereta.index');
    Route::get('/kereta/create', [KeretaController::class, 'create'])->name('kereta.create');
    Route::post('/kereta', [KeretaController::class, 'store'])->name('kereta.store');
    Route::get('/kereta/{id}/edit', [KeretaController::class, 'edit'])->name('kereta.edit');
    Route::put('/kereta/{id}', [KeretaController::class, 'update'])->name('kereta.update');
    Route::delete('/kereta/{id}', [KeretaController::class, 'destroy'])->name('kereta.destroy');

    //================TRIPS==============
    Route::resource('trips', TripController::class);
    Route::get('/trips/{trip}/stations/create', [TripController::class, 'create'])->name('tripstations.create');
    Route::post('/trips/{trip}/stations', [TripController::class, 'store'])->name('tripstations.store');
    Route::get('trips/{trip}/edit', [TripController::class, 'edit'])->name('trips.edit');
    Route::put('trips/{trip}', [TripController::class, 'update'])->name('trips.update');
    Route::delete('trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');

    //================STASIUN==============
    Route::get('/stasiun', [StationController::class, 'index'])->name('stasiun.index');
    Route::get('/stasiun/create', [StationController::class, 'create'])->name('stasiun.create');
    Route::post('/stasiun', [StationController::class, 'store'])->name('stasiun.store');
    Route::get('/stasiun/{station}/edit', [StationController::class, 'edit'])->name('stasiun.edit');
    Route::put('/stasiun/{station}', [StationController::class, 'update'])->name('stasiun.update');
    Route::delete('/stasiun/{station}', [StationController::class, 'destroy'])->name('stasiun.destroy');
    Route::get('/stations/search', [StationController::class, 'searchStations'])->name('stations.search');

    //================BOOKING==============
    Route::post('/booking/{trip}/book', [BookingController::class, 'book'])->name('booking.book');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{booking}/seat/{seat}', [BookingController::class, 'selectSeat'])
        ->name('booking.selectSeat');
    Route::post('/booking/{booking}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::get('/booking/{booking}/ticket', [BookingController::class, 'ticket'])->name('booking.ticket');
    Route::post('/booking/{trip}/passenger', [BookingController::class, 'passengerDetail'])->name('booking.passenger');
    Route::get('/booking/{trip}/seat-select', [BookingController::class, 'seatSelect'])->name('booking.seat.select');
    Route::post('/booking/{booking}/seat-confirm', [BookingController::class, 'seatConfirm'])->name('booking.seat.confirm');
    Route::get('booking/{booking}/seat/{seat}', [BookingController::class, 'selectSeat'])
        ->name('booking.selectSeat');
    Route::get('/bookings/my-tickets', [BookingController::class, 'myTickets'])
        ->middleware('auth')
        ->name('booking.tickets');

    Route::get('/user', function () {
        return view('admin.user.index');
    })->name('user.index');

    
});
