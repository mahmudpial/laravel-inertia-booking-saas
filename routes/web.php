<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');

    Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
});

Route::get('/b/{slug}', [BookingController::class, 'showBookingPage'])->name('booking.page');
Route::get('/api/available-slots', [BookingController::class, 'getAvailableSlots'])->name('api.slots');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

require __DIR__ . '/auth.php';
