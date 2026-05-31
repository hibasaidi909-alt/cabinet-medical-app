<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentApiController;

// Route par défaut dial user (ila knti mkhdma Sanctum)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Les routes dial l-App dialk [cite: 21]
Route::get('/appointments', [AppointmentApiController::class, 'index']);
Route::post('/appointments', [AppointmentApiController::class, 'store']);
Route::get('/appointments/search', [AppointmentApiController::class, 'search']);