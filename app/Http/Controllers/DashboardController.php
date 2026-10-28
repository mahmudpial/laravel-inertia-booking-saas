<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

/**
 * Class DashboardController
 * Managed by CTO - Central Intelligence Node
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ১. সুপার অ্যাডমিন লজিক (Global Oversight)
        if ($user->role === 'super_admin') {
            return Inertia::render('Admin/SuperAdminDashboard', [
                'total_businesses' => Business::count(),
                'total_users' => \App\Models\User::count(),
                'total_revenue' => Booking::whereIn('status', ['confirmed', 'completed'])->join('services', 'bookings.service_id', '=', 'services.id')->sum('services.price'),
            ]);
        }

        // ২. বিজনেস ওনার লজিক (Business Operations)
        if ($user->role === 'owner' || $user->business()->exists()) {
            $business = $user->business;
            if (!$business) {
                return redirect()->route('onboarding.index');
            }

            // Generate last 7 days chart data
            $days = collect(range(6, 0))->map(function ($daysAgo) use ($business) {
                $date = Carbon::today()->subDays($daysAgo);
                $count = $business->bookings()->whereDate('booking_date', $date->toDateString())->count();
                return [
                    'label' => $date->format('D, M j'),
                    'bookings' => $count,
                ];
            });

            return Inertia::render('Dashboard', [
                'stats' => [
                    'total_bookings' => $business->bookings()->count(),
                    'pending_bookings' => $business->bookings()->where('status', 'pending')->count(),
                    'confirmed_bookings' => $business->bookings()->where('status', 'confirmed')->count(),
                    'completed_bookings' => $business->bookings()->where('status', 'completed')->count(),
                    'services_count' => $business->services()->count(),
                    'staff_count' => $business->staff()->count(),
                    'total_earnings' => number_format($business->bookings()->whereIn('status', ['confirmed', 'completed'])->join('services', 'bookings.service_id', '=', 'services.id')->sum('services.price'), 2)
                ],
                'business' => $business,
                'chartData' => [
                    'labels' => $days->pluck('label'),
                    'bookings' => $days->pluck('bookings'),
                ],
                'recentBookings' => $business->bookings()->with(['user', 'service', 'staff'])->latest()->take(6)->get()
            ]);
        }

        // ৩. কাস্টমার লজিক (Discovery & My Agenda) - আপনার রিকোয়ারমেন্ট অনুযায়ী
        return Inertia::render('Customer/DiscoveryHub', [
            'businesses' => Business::all()->map(function ($b) {
                return [
                    'name' => $b->name,
                    'slug' => $b->slug,
                    'description' => $b->description,
                    'logo' => $b->logo ? "/storage/{$b->logo}" : null,
                    'address' => $b->address
                ];
            }),
            'my_upcoming' => $user->bookings()->with(['business', 'service'])->where('booking_date', '>=', Carbon::today())->latest()->take(3)->get()
        ]);
    }
}