<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Availability;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    // বুকing পেজ দেখানোর জন্য
    public function showBookingPage()
    {
        // টেস্ট করার জন্য business_id = 1 এবং এর সব সার্ভিস ফ্রন্টএন্ডে পাঠাচ্ছি
        $business = Business::with('services')->findOrFail(1);

        return Inertia::render('Booking/Create', [
            'business' => $business,
            'services' => $business->services
        ]);
    }

    // নির্দিষ্ট ডেট এবং সার্ভিসের ওপর ভিত্তি করে স্লট জেনারেট করার API
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'required|exists:services,id',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek; // ০ = রবিবার, ১ = সোমবার ... ৬ = शनिवार

        // ১. ওই দিনের ব্যবসার শিডিউল চেক করা
        $availability = Availability::where('business_id', 1)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        // দোকান বন্ধ থাকলে খালি অ্যারে রিটার্ন করবে
        if (!$availability || !$availability->is_open) {
            return response()->json([]);
        }

        // ২. সার্ভিসের ডিউরেশন বা সময় বের করা
        $service = Service::findOrFail($request->service_id);
        $duration = $service->duration_minutes;

        // ৩. স্লট ক্যালকুলেট করা (Carbon ব্যবহার করে)
        $startTime = Carbon::parse($request->date . ' ' . $availability->start_time);
        $endTime = Carbon::parse($request->date . ' ' . $availability->end_time);

        $slots = [];

        // যতক্ষণ পর্যন্ত স্টার্ট টাইম + সার্ভিসের সময় এন্ড টাইমের চেয়ে কম বা সমান থাকে
        while ($startTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slots[] = [
                'time' => $startTime->format('H:i:s'),
                'formatted_time' => $startTime->format('h:i A'),
            ];

            // পরবর্তী স্লটের জন্য টাইম বাড়ানো (এখানে আমরা সার্ভিসের ডিউরেশন অনুযায়ী ইন্টারভাল দিচ্ছি)
            $startTime->addMinutes($duration);
        }

        // দ্রষ্টব্য: পরবর্তী মডিউলে আমরা এই স্লটগুলো থেকে অলরেডি 'booked' স্লটগুলো বাদ দেওয়ার লজিক জুড়ব।
        return response()->json($slots);
    }
}