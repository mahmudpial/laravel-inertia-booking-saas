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
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Public Booking Landing Page
     */
    public function showBookingPage($slug)
    {
        $business = Business::with('services')
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Booking/Create', [
            'business' => $business,
            'services' => $business->services
        ]);
    }

    /**
     * Admin Dashboard: List Bookings
     */
    public function adminIndex()
    {
        // ১. বর্তমানে লগইন করা ইউজারকে নিন
        $user = Auth::user();

        // ২. রিলেশনশিপ লোড করুন (মডেলের রিলেশনশিপের উপর নির্ভর করে)
        $business = \App\Models\Business::where('user_id', $user->id)->first();

        // ৩. যদি বিজনেস না থাকে, তবে ড্যাশবোর্ডে ফেরত পাঠান
        if (!$business) {
            return redirect()->route('dashboard')->with('error', 'আপনার বিজনেস প্রোফাইল এখনো তৈরি হয়নি। অনুগ্রহ করে আগে সেটি সম্পন্ন করুন।');
        }

        $bookings = $business->bookings()
            ->with(['user', 'service'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Bookings', [
            'bookings' => $bookings
        ]);
    }
    /**
     * Update Booking Status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,completed'
        ]);

        $booking = Booking::where('business_id', Auth::user()->business->id)
            ->with(['user', 'service'])
            ->findOrFail($id);

        $booking->update(['status' => $request->status]);

        if ($booking->user) {
            Mail::to($booking->user->email)->send(new BookingNotification($booking, 'customer'));
        }

        return redirect()->back()->with('message', 'Booking status updated successfully!');
    }

    /**
     * Dynamic Slot Generation
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
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

        $existingBookings = Booking::where('business_id', $validated['business_id'])
            ->where('booking_date', $validated['date'])
            ->where('status', '!=', 'canceled')
            ->get();

        while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotEnd = $startTime->copy()->addMinutes($duration);

            $isBooked = $existingBookings->contains(function ($booking) use ($startTime, $slotEnd) {
                $bStart = Carbon::parse($booking->start_time);
                $bEnd = Carbon::parse($booking->end_time);
                return ($startTime < $bEnd && $slotEnd > $bStart);
            });

            if (!$isBooked) {
                $slots[] = ['time' => $startTime->format('H:i:s'), 'formatted' => $startTime->format('h:i A')];
            }
            $startTime->addMinutes($duration);
        }

        return response()->json($slots);
    }

    /**
     * Store Booking
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

        return DB::transaction(function () use ($validated) {
            $service = Service::findOrFail($validated['service_id']);
            $start = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
            $end = $start->copy()->addMinutes($service->duration_minutes);

            $conflict = Booking::where('business_id', $validated['business_id'])
                ->where('booking_date', $validated['booking_date'])
                ->where('status', '!=', 'canceled')
                ->where('start_time', '<', $end->format('H:i:s'))
                ->where('end_time', '>', $start->format('H:i:s'))
                ->exists();

            if ($conflict) {
                return redirect()->back()->withErrors(['time' => 'This slot is no longer available.']);
            }

            $booking = Booking::create([
                'business_id' => $validated['business_id'],
                'user_id' => Auth::id() ?? 1,
                'service_id' => $validated['service_id'],
                'booking_date' => $validated['booking_date'],
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            $booking->load(['business.user', 'service']);
            Mail::to(Auth::user()->email ?? 'customer@example.com')->send(new BookingNotification($booking, 'customer'));

            if ($booking->business->user) {
                Mail::to($booking->business->user->email)->send(new BookingNotification($booking, 'admin'));
            }

            return redirect()->back()->with('message', 'Booking successful!');
        });
    }
}