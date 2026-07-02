<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Booking;

class CalendarController extends Controller
{
    public function index()
    {
        $business = auth()->user()->business;

        // বিজনেস প্রোফাইল না থাকলে অনবোর্ডিং-এ পাঠানো
        if (!$business) {
            return redirect()->route('onboarding.index');
        }

        // এই অ্যাডমিনের বিজনেসের সব বুকিং রিলেশনসহ নিয়ে আসা
        $bookings = $business->bookings()
            ->with(['user', 'service'])
            ->get();

        return Inertia::render('Admin/Calendar', [
            'bookings' => $bookings,
            'business' => $business
        ]);
    }
}