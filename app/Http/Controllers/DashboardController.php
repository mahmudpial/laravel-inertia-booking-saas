<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // ১. লগইন করা ইউজারের ব্যবসা খুঁজে বের করা
        $business = auth()->user()->business;

        // যদি কোনো ব্যবসা না থাকে, তবে ডিফল্ট জিরো ডাটা পাঠানো
        if (!$business) {
            return Inertia::render('Dashboard', [
                'stats' => [
                    'total_bookings' => 0,
                    'pending_bookings' => 0,
                    'total_earnings' => '0.00'
                ]
            ]);
        }

        // ২. মোট বুকিং সংখ্যা গণনা (Total Bookings)
        $totalBookings = $business->bookings()->count();

        // ৩. পেন্ডিং বুকিং সংখ্যা গণনা (Pending Bookings)
        $pendingBookings = $business->bookings()->where('status', 'pending')->count();

        // ৪. মোট আয় গণনা (Total Earnings)
        // [FIXED] 'bookings::service_id' পরিবর্তন করে 'bookings.service_id' করা হয়েছে
        $totalEarnings = $business->bookings()
            ->whereIn('status', ['confirmed', 'completed'])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'total_earnings' => number_format($totalEarnings, 2)
            ]
        ]);
    }
}