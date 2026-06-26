<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * Retrieve the business associated with the currently authenticated user.
         * If no business is found, return a default dashboard view with zeroed statistics 
         * and null business data to ensure the frontend renders gracefully.
         */
        $business = auth()->user()->business;

        if (!$business) {
            return Inertia::render('Dashboard', [
                'stats' => [
                    'total_bookings' => 0,
                    'pending_bookings' => 0,
                    'total_earnings' => '0.00'
                ],
                'business' => null
            ]);
        }

        /**
         * Calculate key performance indicators for the business dashboard.
         * This includes the total count of bookings, the number of pending appointments,
         * and the total generated revenue from confirmed or completed service bookings.
         */
        $totalBookings = $business->bookings()->count();
        $pendingBookings = $business->bookings()->where('status', 'pending')->count();

        $totalEarnings = $business->bookings()
            ->whereIn('status', ['confirmed', 'completed'])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        /**
         * Return the dashboard view populated with computed statistics and business details.
         * The business information, specifically the slug, is passed to the frontend to 
         * facilitate dynamic URL generation for the public booking page.
         */
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_bookings' => $totalBookings,
                'pending_bookings' => $pendingBookings,
                'total_earnings' => number_format($totalEarnings, 2)
            ],
            'business' => [
                'name' => $business->name,
                'slug' => $business->slug
            ]
        ]);
    }
}