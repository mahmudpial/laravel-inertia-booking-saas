<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\Scopes\TenantScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\BookingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Public Booking Landing Page
     * Public route needs to bypass TenantScope to load targeted tenant data via slug
     */
    public function showBookingPage($slug)
    {
        // Public Flow: User is a guest, explicitly bypassing TenantScope to fetch the business ecosystem
        $business = Business::with([
            'services.addons',
            'staff' => function ($query) {
                $query->where('is_active', true);
            }
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Booking/Create', [
            'business' => $business,
            'services' => $business->services,
            'staff' => $business->staff
        ]);
    }

    /**
     * Admin Dashboard: List Bookings
     * TenantScope safely auto-injects business_id context
     */
    public function adminIndex()
    {
        if (!Auth::user()->business_id && !Auth::user()->is_super_admin) {
            return redirect()->route('onboarding.index');
        }

        // Clean: No manual business relationship queries needed. TenantScope secures this.
        $bookings = Booking::with(['user', 'service', 'staff', 'addons'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Bookings', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Update Booking Status & Schedule (Calendar Sync)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,canceled,completed',
            'booking_date' => 'nullable|date',
            'start_time' => 'nullable',
        ]);

        // Clean: TenantScope implicitly enforces business isolation
        $booking = Booking::findOrFail($id);

        // Handle Re-scheduling (Drag & Drop)
        if ($request->filled('booking_date') && $request->filled('start_time')) {
            $newStart = Carbon::parse($request->booking_date . ' ' . $request->start_time);
            $newEnd = $newStart->copy()->addMinutes($booking->service->duration_minutes);

            // Conflict Check: Scoped dynamically by the applied TenantScope
            $hasConflict = Booking::where('id', '!=', $id)
                ->where('staff_id', $booking->staff_id)
                ->where('booking_date', $request->booking_date)
                ->where('status', '!=', 'canceled')
                ->where('start_time', '<', $newEnd->format('H:i:s'))
                ->where('end_time', '>', $newStart->format('H:i:s'))
                ->exists();

            if ($hasConflict) {
                return redirect()->back()->withErrors(['message' => 'Staff collision detected at the target window.']);
            }

            $booking->update([
                'booking_date' => $request->booking_date,
                'start_time' => $newStart->format('H:i:s'),
                'end_time' => $newEnd->format('H:i:s'),
            ]);
        }

        if ($request->filled('status')) {
            $booking->update(['status' => $request->status]);
        }

        return redirect()->back()->with('message', 'Deployment Synchronized.');
    }

    /**
     * Dynamic Slot Generation
     * Unauthenticated guest flow requires explicit business context filtering
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
        ]);

        $date = Carbon::parse($validated['date']);

        // Guest contexts need explicit scope bypass to target the request parameters accurately
        $availability = Availability::withoutGlobalScope(TenantScope::class)
            ->where('business_id', $validated['business_id'])
            ->where('day_of_week', $date->dayOfWeek)
            ->first();

        if (!$availability || !$availability->is_open) {
            return response()->json([]);
        }

        $service = Service::withoutGlobalScope(TenantScope::class)->findOrFail($validated['service_id']);
        $duration = $service->duration_minutes;
        $slots = [];

        $startTime = Carbon::parse($validated['date'] . ' ' . $availability->start_time);
        $endTime = Carbon::parse($validated['date'] . ' ' . $availability->end_time);

        // Fetch existing bookings specifically for the targeted scope context
        $existingBookings = Booking::withoutGlobalScope(TenantScope::class)
            ->where('business_id', $validated['business_id'])
            ->where('staff_id', $validated['staff_id'])
            ->where('booking_date', $validated['date'])
            ->where('status', '!=', 'canceled')
            ->get();

        while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotEnd = $startTime->copy()->addMinutes($duration);

            $isBooked = $existingBookings->contains(function ($b) use ($startTime, $slotEnd) {
                $bStart = Carbon::parse($b->start_time);
                $bEnd = Carbon::parse($b->end_time);
                return ($startTime < $bEnd && $slotEnd > $bStart);
            });

            if (!$isBooked) {
                $slots[] = [
                    'time' => $startTime->format('H:i:s'),
                    'formatted' => $startTime->format('h:i A')
                ];
            }
            $startTime->addMinutes($duration);
        }

        return response()->json($slots);
    }

    /**
     * Store Public Booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'addon_ids' => 'nullable|array',
            'addon_ids.*' => 'exists:service_addons,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:1000'
        ]);

        return DB::transaction(function () use ($validated) {
            $service = Service::withoutGlobalScope(TenantScope::class)->findOrFail($validated['service_id']);
            $start = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
            $end = $start->copy()->addMinutes($service->duration_minutes);

            // Double check for staff collision explicitly bypassing current user scope if guest executes it
            $conflict = Booking::withoutGlobalScope(TenantScope::class)
                ->where('business_id', $validated['business_id'])
                ->where('staff_id', $validated['staff_id'])
                ->where('booking_date', $validated['booking_date'])
                ->where('status', '!=', 'canceled')
                ->where('start_time', '<', $end->format('H:i:s'))
                ->where('end_time', '>', $start->format('H:i:s'))
                ->exists();

            if ($conflict) {
                return redirect()->back()->withErrors(['time' => 'This window was just secured by another packet.']);
            }

            // Create Primary Booking Record -> BelongsToTenant will safely catch this or fallback to explicitly passed business_id
            $booking = Booking::create([
                'business_id' => $validated['business_id'],
                'user_id' => Auth::id(),
                'service_id' => $validated['service_id'],
                'staff_id' => $validated['staff_id'],
                'booking_date' => $validated['booking_date'],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            if (!empty($validated['addon_ids'])) {
                $booking->addons()->attach($validated['addon_ids']);
            }

            // Safe lookup bypassing global scope strictly to ensure relation mappings resolve fine for notifications
            $booking->load([
                'business' => function ($q) {
                    $q->withoutGlobalScope(TenantScope::class);
                },
                'business.user',
                'service',
                'staff'
            ]);

            try {
                if (Auth::check()) {
                    Mail::to(Auth::user()->email)->send(new BookingNotification($booking, 'customer'));
                }
                if ($booking->business && $booking->business->user) {
                    Mail::to($booking->business->user->email)->send(new BookingNotification($booking, 'admin'));
                }
            } catch (\Exception $e) {
                report($e);
            }

            return redirect()->back()->with('message', 'Session Authorized and Indexed.');
        });
    }
}