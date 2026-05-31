<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [AppointmentController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'ar'])) {session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
require __DIR__.'/auth.php';
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('appointments', AppointmentController::class);
});
Route::middleware(['auth', 'verified'])->group(function () {
    // الـ Route ديال البحث الديناميكي
    Route::get('appointments/search', [AppointmentController::class, 'search'])->name('appointments.search');
    
    Route::resource('appointments', AppointmentController::class);
});