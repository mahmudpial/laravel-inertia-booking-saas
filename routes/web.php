<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\AvailabilityController;

// ১. কাস্টমারদের জন্য ল্যান্ডিং বা ওয়েলকাম পেজ
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ২. প্রোফাইল ম্যানেজমেন্ট রাউটস (লগইন করা যেকোনো ইউজারের জন্য)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ৩. প্রটেক্টেড অ্যাডমিন রাউটস (লগইন এবং ভেরিফাইড ইউজারদের জন্য)
Route::middleware(['auth', 'verified'])->group(function () {

    // বিজনেস অনবোর্ডিং ফ্লো রাউটস
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // রিয়েল-টাইম ড্যাশবোর্ড ওভারভিউ (ডুপ্লিকেটটি রিমুভ করে কন্ট্রোলার রাউট রাখা হলো)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // সার্ভিস ম্যানেজমেন্ট CRUD রাউটস (নামগুলো লেআউটের সাথে মিল রেখে 'admin.services.*' করা হলো)
    Route::get('/admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::post('/admin/services', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::put('/admin/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');

    // বুকিং রিকোয়েস্ট ও স্ট্যাটাস ম্যানেজমেন্ট রাউটস
    Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');

    // বিজনেস আওয়ার্স বা কাজের সময় ম্যানেজমেন্ট রাউটস
    Route::get('/admin/availability', [AvailabilityController::class, 'index'])->name('admin.availability.index');
    Route::put('/admin/availability', [AvailabilityController::class, 'update'])->name('admin.availability.update');
});

// ৪. পাবলিক বুকিং রাউটস (কাস্টমাররা যেখান থেকে অ্যাপয়েন্টমেন্ট বুক করবে)
Route::get('/b/{slug}', [BookingController::class, 'showBookingPage'])->name('booking.page');
Route::get('/api/available-slots', [BookingController::class, 'getAvailableSlots'])->name('api.slots');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

require __DIR__ . '/auth.php';