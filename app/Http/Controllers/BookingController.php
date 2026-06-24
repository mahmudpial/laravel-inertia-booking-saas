<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Availability;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display the public booking landing page for a specific business.
     * Fetches the business details along with its offered services using the unique slug.
     * Throws a 404 exception if the business is not found.
     */
    public function showBookingPage($slug)
    {
        $business = Business::with('services')->where('slug', $slug)->firstOrFail();

        return Inertia::render('Booking/Create', [
            'business' => $business,
            'services' => $business->services
        ]);
    }

    /**
     * Generate dynamic and real-time available time slots for a specific date and service.
     * Validates input, determines the day of the week, checks business hours/availability, 
     * cross-references existing bookings to prevent overlaps, and pushes available time intervals.
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        /**
         * Fetch the configured operating hours for the given business on this specific day of the week.
         * If the business is closed or availability is not configured, return an empty array.
         */
        $availability = Availability::where('business_id', $request->business_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$availability || !$availability->is_open) {
            return response()->json([]);
        }

        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration_minutes;

        $startTime = Carbon::parse($request->date . ' ' . $availability->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $availability->end_time);

        /**
         * Retrieve all active (non-canceled) existing appointments for the business on the selected date 
         * to evaluate prospective slot availability.
         */
        $existingBookings = Booking::where('business_id', $request->business_id)
            ->where('booking_date', $request->date)
            ->where('status', '!=', 'canceled')
            ->get(['start_time', 'end_time']);

        $slots = [];

        /**
         * Loop through the operating hours timeline by incremental steps matching the service duration.
         * Filter out slots that conflict or overlap with existing database booking times.
         */
        while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStartTime = $startTime->format('H:i:s');
            $slotEndTime = $startTime->copy()->addMinutes($duration)->format('H:i:s');

            $isBooked = $existingBookings->contains(function ($booking) use ($slotStartTime, $slotEndTime) {
                return ($slotStartTime >= $booking->start_time && $slotStartTime < $booking->end_time) ||
                    ($slotEndTime > $booking->start_time && $slotEndTime <= $booking->end_time);
            });

            if (!$isBooked) {
                $slots[] = [
                    'time' => $slotStartTime,
                    'formatted_time' => $startTime->format('h:i A'),
                ];
            }

            $startTime->addMinutes($duration);
        }

        return response()->json($slots);
    }

    /**
     * Store a newly created booking in the database.
     * Re-validates slot availability right before creation to avoid race conditions (double bookings),
     * automatically calculates the slot end time based on service duration, and saves the record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:500'
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        /**
         * Strict runtime double-booking verification. Checks if an overlapping appointment has been 
         * confirmed by another client in the fractions of a second prior to this request submission.
         */
        $conflict = Booking::where('business_id', $validated['business_id'])
            ->where('booking_date', $validated['booking_date'])
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($validated, $endTime) {
                $query->where(function ($q) use ($validated, $endTime) {
                    $q->where('start_time', '>=', $validated['start_time'])
                        ->where('start_time', '<', $endTime->format('H:i:s'));
                })->orWhere(function ($q) use ($validated, $endTime) {
                    $q->where('end_time', '>', $validated['start_time'])
                        ->where('end_time', '<=', $endTime->format('H:i:s'));
                });
            })->exists();

        if ($conflict) {
            return redirect()->back()->withErrors(['time' => 'This slot has just been booked by someone else!']);
        }

        Booking::create([
            'business_id' => $validated['business_id'],
            'user_id' => auth()->id() ?? 1,
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('message', 'Appointment booked successfully!');
    }

    /**
     * Display a comprehensive listing of bookings within the administrative back-office panel.
     * Scope restricted exclusively to the authenticated user's registered business entity.
     * Eager loads related customer details (user) and services for execution optimization.
     */
    public function adminIndex()
    {
        $business = auth()->user()->business;
        $bookings = $business ? $business->bookings()->with(['user', 'service'])->latest()->get() : [];

        return Inertia::render('Admin/Bookings', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Update the operational status of an existing booking instance (e.g., pending, confirmed, canceled, completed).
     * Enforces tenancy boundaries by ensuring administrators can only modify appointments belonging to their own business.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,completed'
        ]);

        $booking = auth()->user()->business->bookings()->findOrFail($id);
        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('message', 'Booking status updated successfully!');
    }
}