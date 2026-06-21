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
    // ইউআরএল থেকে $slug রিসিভ করা
    public function showBookingPage($slug)
    {
        // স্লগ অনুযায়ী ব্যবসা খুঁজে বের করা, না পেলে ৪0৪ এরর দেবে
        $business = Business::with('services')->where('slug', $slug)->firstOrFail();

        return Inertia::render('Booking/Create', [
            'business' => $business,
            'services' => $business->services
        ]);
    }
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id', // ফ্রন্টএন্ড থেকে বিজনেস আইডি আসবে
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        // ফিক্সড ১ এর বদলে রিকোয়েস্ট থেকে আসা বিজনেস আইডি ব্যবহার করা
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

    // 🔥 নতুন মেথড: বুকিং ডাটাবেসে সেভ করা
    // বুকিং স্টোর করার মেথড আপডেট
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id', // নতুন সংযোজন
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

        Booking::create([
            'business_id' => $validated['business_id'],
            'user_id' => auth()->id() ?? 1, // লগইন করা ইউজার থাকলে তার আইডি, না থাকলে ডিফল্ট ১
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('message', 'Appointment booked successfully!');
    }

    public function adminIndex()
    {
        $business = auth()->user()->business;
        $bookings = $business ? $business->bookings()->with(['user', 'service'])->latest()->get() : [];

        return Inertia::render('Admin/Bookings', [
            'bookings' => $bookings
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,completed'
        ]);

        // নিশ্চিত করা হচ্ছে যে অ্যাডমিন শুধু তার নিজের ব্যবসার বুকিংই আপডেট করতে পারছেন
        $booking = auth()->user()->business->bookings()->findOrFail($id);
        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('message', 'Booking status updated successfully!');
    }
}