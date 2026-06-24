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
use App\Http\Controllers\BusinessSettingsController;

/**
 * Public Landing and Marketing Overview
 * Renders the primary application welcome screen with essential session status
 * for authorization and system engine properties.
 */
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/**
 * Authenticated Profile Tenancy Controls
 * Standard core account mutation layer protected via standard session authorization state layers.
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Administrative SaaS Dashboard Back-Office Suite
 * Enforces authenticated multi-tenant restrictions for administrative operations,
 * configuration controls, and client arrangement tracking structures.
 */
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * Tenant Business Onboarding Mechanism
     * Restricts initial administrative authorization states until necessary organizational profiling data is compiled.
     */
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    /**
     * Centralized Real-Time Metric Performance Index
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * Service Inventory Management Operations (CRUD)
     */
    Route::get('/admin/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::post('/admin/services', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::put('/admin/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');

    /**
     * Client Booking Matrix Overviews and Structural Status Updates
     */
    Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::patch('/admin/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('admin.bookings.updateStatus');

    /**
     * Operational Working Hours Matrix Configurations
     */
    Route::get('/admin/availability', [AvailabilityController::class, 'index'])->name('admin.availability.index');
    Route::put('/admin/availability', [AvailabilityController::class, 'update'])->name('admin.availability.update');

    // Business Profile and Branding Customization Layer
    Route::get('/admin/settings', [BusinessSettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::post('/admin/settings', [BusinessSettingsController::class, 'update'])->name('admin.settings.update');
});

/**
 * Public Front-Facing Client Booking Sub-System
 * Globally exposed endpoints allowing unauthenticated customer traffic to lookup business timelines,
 * verify runtime available service intervals, and commit booking payloads.
 */

// Route to display the business-specific public booking page layout using the unique slug identifier
Route::get('/b/{slug}', [BookingController::class, 'showBookingPage'])->name('booking.page');

// Endpoint utilized by Vue.js watchers to calculate open intervals on the selected date
Route::get('/api/slots', [BookingController::class, 'getAvailableSlots'])->name('api.slots');

// Secure endpoint to post client registration entries into the application ledger database
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

require __DIR__ . '/auth.php';