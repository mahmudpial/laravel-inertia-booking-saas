<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\Staff;
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
     * Updated to load Add-ons and Staff Roster
     */
    public function showBookingPage($slug)
    {
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
     */
    public function adminIndex()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('onboarding.index');
        }

        $bookings = $business->bookings()
            ->with(['user', 'service', 'staff', 'addons'])
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
        $business = Auth::user()->business;

        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,canceled,completed',
            'booking_date' => 'nullable|date',
            'start_time' => 'nullable',
        ]);

        $booking = Booking::where('business_id', $business->id)->findOrFail($id);

        // Handle Re-scheduling (Drag & Drop)
        if ($request->filled('booking_date') && $request->filled('start_time')) {
            $newStart = Carbon::parse($request->booking_date . ' ' . $request->start_time);
            // Re-calculate end time based on original service duration
            $newEnd = $newStart->copy()->addMinutes($booking->service->duration_minutes);

            // Conflict Check: Check specifically for the assigned staff member
            $hasConflict = Booking::where('business_id', $business->id)
                ->where('id', '!=', $id)
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
     * Updated: Slots are now filtered by specific Staff Member
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
        $availability = Availability::where('business_id', $validated['business_id'])
            ->where('day_of_week', $date->dayOfWeek)
            ->first();

        if (!$availability || !$availability->is_open) {
            return response()->json([]);
        }

        $service = Service::findOrFail($validated['service_id']);
        $duration = $service->duration_minutes;
        $slots = [];

        $startTime = Carbon::parse($validated['date'] . ' ' . $availability->start_time);
        $endTime = Carbon::parse($validated['date'] . ' ' . $availability->end_time);

        // Fetch existing bookings ONLY for the selected staff member
        $existingBookings = Booking::where('business_id', $validated['business_id'])
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
     * Updated: Handles Staff ID and Add-on pivot syncing
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
            $service = Service::findOrFail($validated['service_id']);
            $start = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
            $end = $start->copy()->addMinutes($service->duration_minutes);

            // Double check for staff collision before final commit
            $conflict = Booking::where('business_id', $validated['business_id'])
                ->where('staff_id', $validated['staff_id'])
                ->where('booking_date', $validated['booking_date'])
                ->where('status', '!=', 'canceled')
                ->where('start_time', '<', $end->format('H:i:s'))
                ->where('end_time', '>', $start->format('H:i:s'))
                ->exists();

            if ($conflict) {
                return redirect()->back()->withErrors(['time' => 'This window was just secured by another packet.']);
            }

            // Create Primary Booking Record
            $booking = Booking::create([
                'business_id' => $validated['business_id'],
                'user_id' => Auth::id() ?? 1,
                'service_id' => $validated['service_id'],
                'staff_id' => $validated['staff_id'],
                'booking_date' => $validated['booking_date'],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            // Sync Enhancement Packets (Add-ons)
            if (!empty($validated['addon_ids'])) {
                $booking->addons()->attach($validated['addon_ids']);
            }

            $booking->load(['business.user', 'service', 'staff']);

            // Technical Dispatch: Notifications
            try {
                Mail::to(Auth::user()->email)->send(new BookingNotification($booking, 'customer'));
                if ($booking->business->user) {
                    Mail::to($booking->business->user->email)->send(new BookingNotification($booking, 'admin'));
                }
            } catch (\Exception $e) {
                report($e);
            }

            return redirect()->back()->with('message', 'Session Authorized and Indexed.');
        });
    }
}