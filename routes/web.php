<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\App;

// 1. Route principale '/' kat-kheddem automatique l-dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

//  1. Page Dashboard (Graphiques)
Route::get('/dashboard', [AppointmentController::class, 'dashboard'])->name('dashboard');

//  2. Page Rendez-vous / Patients (Ha l-tableau dyalk jani hna)
Route::get('/appointments-list', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
Route::get('/appointments/search', [AppointmentController::class, 'search']);