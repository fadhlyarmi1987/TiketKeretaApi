<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainApiController;

Route::get('/trains', [TrainApiController::class, 'index']);
