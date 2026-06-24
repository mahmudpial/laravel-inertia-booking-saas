<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Availability;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\BookingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display the public booking landing page for a specific business.
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

        $existingBookings = Booking::where('business_id', $request->business_id)
            ->where('booking_date', $request->date)
            ->where('status', '!=', 'canceled')
            ->get(['start_time', 'end_time']);

        $slots = [];

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

        $booking = Booking::create([
            'business_id' => $validated['business_id'],
            'user_id' => Auth::id() ?? 1,
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        // Freshly load relations before dispatching the mail templates safely
        $booking->load(['business.user', 'service']);

        $customerEmail = Auth::user()->email ?? 'customer@example.com';
        Mail::to($customerEmail)->send(new BookingNotification($booking, 'customer'));

        if ($booking->business && $booking->business->user) {
            Mail::to($booking->business->user->email)->send(new BookingNotification($booking, 'admin'));
        }

        return redirect()->back()->with('message', 'Appointment booked successfully!');
    }

    /**
     * Display a comprehensive listing of bookings within the administrative panel.
     */
    public function adminIndex()
    {
        $business = Auth::user()->business;
        $bookings = $business ? $business->bookings()->with(['user', 'service'])->latest()->get() : [];

        return Inertia::render('Admin/Bookings', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Update the operational status of an existing booking instance.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,completed'
        ]);

        // CRITICAL CRASH FIX: Added 'service' relation to eager loading so the email blade doesn't throw a null error!
        $booking = Auth::user()->business->bookings()->with(['user', 'business', 'service'])->findOrFail($id);

        $booking->update(['status' => $request->status]);

        // Dispatch email notification securely
        $customerEmail = $booking->user->email ?? Auth::user()->email;
        Mail::to($customerEmail)->send(new BookingNotification($booking, 'customer'));

        return redirect()->back()->with('message', 'Booking status updated successfully!');
    }
}