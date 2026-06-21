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
    public function showBookingPage()
    {
        $business = Business::with('services')->findOrFail(1);
        return Inertia::render('Booking/Create', [
            'business' => $business,
            'services' => $business->services
        ]);
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        $availability = Availability::where('business_id', 1)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$availability || !$availability->is_open) {
            return response()->json([]);
        }

        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration_minutes;

        $startTime = Carbon::parse($request->date . ' ' . $availability->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $availability->end_time);

        // 🔥 নতুন সংযোজন: ওই নির্দিষ্ট ডেটে অলরেডি যে বুকিংগুলো আছে তা ডাটাবেস থেকে নেওয়া
        $existingBookings = Booking::where('business_id', 1)
            ->where('booking_date', $request->date)
            ->where('status', '!=', 'canceled')
            ->get(['start_time', 'end_time']);

        $slots = [];

        while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStartTime = $startTime->format('H:i:s');
            $slotEndTime = $startTime->copy()->addMinutes($duration)->format('H:i:s');

            // 🔥 লজিক: এই স্লটটি কি অলরেডি বুকড কোনো সময়ের ভেতরে পড়ছে?
            $isBooked = $existingBookings->contains(function ($booking) use ($slotStartTime, $slotEndTime) {
                // বুকিংয়ের সময়ের সাথে ওভারল্যাপ চেক করা
                return ($slotStartTime >= $booking->start_time && $slotStartTime < $booking->end_time) ||
                    ($slotEndTime > $booking->start_time && $slotEndTime <= $booking->end_time);
            });

            // যদি বুকড না হয়ে থাকে, কেবল তখনই কাস্টমারকে স্লটটি দেখানো হবে
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:500'
        ]);

        $service = Service::findOrFail($validated['service_id']);

        // এন্ড টাইম ক্যালকুলেট করা (স্টার্ট টাইমের সাথে সার্ভিসের ডিউরেশন যোগ করে)
        $startTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        // ডাবল বুকিং প্রিভেন্ট করার জন্য শেষবারের মতো ব্যাকএন্ড ভ্যালিডেশন
        $conflict = Booking::where('business_id', 1)
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

        // বুকিং ক্রিয়েট করা (সাময়িকভাবে user_id = 1 ধরে নিচ্ছি, পরে এটি auth()->id() হবে)
        Booking::create([
            'business_id' => 1,
            'user_id' => 1,
            'service_id' => $validated['service_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'confirmed', // অটো-কনফার্ম করে দিচ্ছি আপাতত
            'notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('message', 'Appointment booked successfully!');
    }
}