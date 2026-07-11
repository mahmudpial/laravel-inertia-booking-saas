<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

/**
 * TACTICAL CONTROLLER REGISTRY
 * --------------------------------------------------------------------------
 * Managed by CTO - Central Intelligence Node
 */
use App\Http\Controllers\{
    ProfileController,
    ServiceController,
    BookingController,
    DashboardController,
    OnboardingController,
    AvailabilityController,
    BusinessSettingsController,
    CalendarController,
    StaffController,
    CustomerDashboardController,
    SuperAdminController
};

/*
|--------------------------------------------------------------------------
| 01. PUBLIC OPERATIONAL LAYER
|--------------------------------------------------------------------------
| High-level entry points accessible by unauthenticated traffic.
| These nodes handle discovery, slot quantization, and packet dispatch.
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
 * Public Discovery: Customer-Facing Business Portals
 */
Route::get('/b/{slug}', [BookingController::class, 'showBookingPage'])->name('booking.page');

/** 
 * Public API: Dynamic Slot Quantization (Ajax/Axios)
 */
Route::get('/api/available-slots', [BookingController::class, 'getAvailableSlots'])->name('api.slots');

/** 
 * Secure Transmission: Authenticated Booking Packet Dispatch
 */
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store')->middleware('auth');


/*
|--------------------------------------------------------------------------
| 02. AUTHENTICATED CORE INFRASTRUCTURE
|--------------------------------------------------------------------------
| Secured via Session Verification Handshake.
| These routes are universal for all authenticated nodes.
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /** 
     * Universal Command Hub (The Multi-Role Dashboard)
     * Directs traffic to specific Hubs (Owner vs Customer) internally.
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** 
     * Account Identity Management
     * Personal profile mutation logic.
     */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->middleware('password.confirm')->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | 03. CLIENT PORTAL (The Agenda)
    |----------------------------------------------------------------------
    | Restricted modules for standard Customer nodes.
    */
    Route::prefix('my-agenda')->name('customer.')->group(function () {
        Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::patch('/{id}/cancel', [CustomerDashboardController::class, 'cancel'])->name('bookings.cancel');
    });

    /*
    |----------------------------------------------------------------------
    | 04. ADMINISTRATIVE SUITE (Business Owner Nodes)
    |----------------------------------------------------------------------
    | Enterprise modules protected by the 'admin' authorization alias.
    | These routes manage a single business instance.
    */
    Route::middleware(['admin'])->group(function () {

        // --- Infrastructure Initialization ---
        Route::prefix('onboarding')->name('onboarding.')->group(function () {
            Route::get('/', [OnboardingController::class, 'index'])->name('index');
            Route::post('/', [OnboardingController::class, 'store'])->name('store');
        });

        // --- Inventory & SKU Hub ---
        Route::prefix('admin/services')->name('admin.services.')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('index');
            Route::post('/', [ServiceController::class, 'store'])->name('store');
            Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
            Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
        });

        // --- Roster: Specialist Deployment ---
        Route::prefix('admin/staff')->name('admin.staff.')->group(function () {
            Route::get('/', [StaffController::class, 'index'])->name('index');
            Route::post('/', [StaffController::class, 'store'])->name('store');
            Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');
        });

        // --- Operational Control: Queue & Timeline ---
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings.index');
            Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
            Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        });

        // --- Temporal Sync: Availability Settings ---
        Route::prefix('admin/availability')->name('admin.availability.')->group(function () {
            Route::get('/', [AvailabilityController::class, 'index'])->name('index');
            Route::put('/', [AvailabilityController::class, 'update'])->name('update');
        });

        // --- Brand Studio: Identity Sync ---
        Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
            Route::get('/', [BusinessSettingsController::class, 'edit'])->name('edit');
            Route::post('/', [BusinessSettingsController::class, 'update'])->middleware('password.confirm')->name('update');
        });
    });

    /*
    |----------------------------------------------------------------------
    | 05. SOVEREIGN OVERLORD SUITE (Super Admin Node)
    |----------------------------------------------------------------------
    | Global oversight and cross-business infrastructure monitoring.
    | Protected by the 'super_admin' sovereign handshake.
    */
    Route::middleware(['super_admin'])->prefix('system-control')->name('superadmin.')->group(function () {

        // --- Platform Oversight (Main Hub) ---
        Route::get('/oversight', [SuperAdminController::class, 'dashboard'])->name('dashboard');

        // --- Entity Management (Node Ledger) ---
        Route::get('/entities', [SuperAdminController::class, 'entities'])->name('entities.index');
        Route::patch('/entities/{id}/toggle', [SuperAdminController::class, 'toggleNodeStatus'])->name('entities.toggle');

        // --- Financial Intelligence ---
        Route::get('/financials', [SuperAdminController::class, 'financials'])->name('financials');

        // --- Security & Audit Logs ---
        Route::get('/audit-ledger', [SuperAdminController::class, 'audit'])->name('audit');

        // --- System Core Configuration ---
        Route::get('/config', [SuperAdminController::class, 'config'])->name('config');
    });

});

require __DIR__ . '/auth.php';