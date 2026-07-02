<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

/**
 * Class SuperAdminController
 * 
 * SOVEREIGN CONTROL NODE:
 * This controller is the primary oversight engine for the SmartBooking SaaS grid.
 * It manages global entity ledgers, financial trajectories, and system-wide 
 * configurations, bypassing individual tenant constraints.
 */
class SuperAdminController extends Controller
{
    /**
     * Display the Sovereign Oversight Dashboard (Global Hub).
     * 
     * Workflow:
     * 1. Aggregate macro KPIs (Global Users, Active Nodes, Platform Revenue).
     * 2. Synchronize 30-day yield trajectory for financial monitoring.
     * 3. Profile system-wide node integrity and status.
     */
    public function dashboard(): Response
    {
        // 01. GLOBAL KPI AGGREGATION
        $totalUsers = User::count();
        $nodeStats = [
            'total' => Business::count(),
            'active' => Business::where('status', 'active')->count(),
            'suspended' => Business::where('status', 'suspended')->count(),
        ];

        // Platform-wide Gross Revenue (Cumulative sum of all session values)
        $globalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        // 02. FINANCIAL TRAJECTORY (30-Day Real-Time Cycle)
        $revenueTrend = Booking::whereIn('status', ['confirmed', 'completed'])
            ->where('bookings.created_at', '>=', Carbon::now()->subDays(30))
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->select(
                DB::raw('DATE(bookings.created_at) as date'),
                DB::raw('SUM(services.price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 03. RECENT ENTITY LEDGER (Latest 5 nodes)
        $recentEntities = Business::with('user')
            ->withCount('bookings')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'name' => $b->name,
                'owner' => $b->user->name ?? 'Unverified Principal',
                'slug' => $b->slug,
                'load' => $b->bookings_count,
                'status' => ucfirst($b->status ?? 'Active'),
                'created_at' => $b->created_at->format('M d, Y')
            ]);

        return Inertia::render('Admin/SuperAdminDashboard', [
            'stats' => [
                'users' => number_format($totalUsers),
                'businesses' => $nodeStats,
                'revenue' => number_format($globalRevenue, 2),
                'mrr' => number_format($globalRevenue / 12, 2), // Simulated Monthly Yield
            ],
            'chartData' => [
                'labels' => $revenueTrend->pluck('date'),
                'revenue' => $revenueTrend->pluck('revenue'),
            ],
            'recentEntities' => $recentEntities
        ]);
    }

    /**
     * Access the Global Entity Ledger.
     * Provides a granular view of all business nodes registered on the platform.
     */
    public function entities()
    {
        $entities = Business::with('user')
            ->withCount(['bookings', 'services'])
            ->latest()
            ->paginate(15)
            ->through(fn($b) => [
                'id' => $b->id,
                'name' => $b->name,
                'owner' => $b->user->name ?? 'Unverified Principal',
                'email' => $b->user->email ?? 'N/A',
                'status' => $b->status ?? 'active',
                'load' => $b->bookings_count,
                'skus' => $b->services_count,
                'slug' => $b->slug,
                'joined' => $b->created_at->format('M d, Y')
            ]);

        return Inertia::render('Admin/SuperAdmin/Entities', [
            'entities' => $entities
        ]);
    }

    /**
     * Execute Node Status Mutation (Toggle Active/Suspended).
     * Security Handshake required to disconnect a business node.
     */
    public function toggleNodeStatus($id): RedirectResponse
    {
        $business = Business::findOrFail($id);
        $newStatus = ($business->status === 'active') ? 'suspended' : 'active';

        $business->update(['status' => $newStatus]);

        return redirect()->back()->with('message', "Infrastructure node #{$id} status updated to: {$newStatus}");
    }

    /**
     * Financial Intelligence Node.
     * Macro-oversight of platform transactions and commission yields.
     *
     * NOTE: There is no payment processor wired up yet, so "platform yield"
     * is a projection based on an assumed flat commission rate on paid volume.
     */
    public function financials(): Response
    {
        $commissionRate = 0.12; // 12% assumed platform commission
        $paidStatuses = ['confirmed', 'completed'];

        $grossRevenue = (float) Booking::whereIn('status', $paidStatuses)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        $totalVolume = Booking::count();
        $paidVolume = Booking::whereIn('status', $paidStatuses)->count();

        $statusBreakdown = Booking::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 6-month revenue trajectory
        // NOTE: grouped in PHP (not SQL DATE_FORMAT/strftime) so this works
        // identically across MySQL, MariaDB, SQLite, Postgres, etc.
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();

        $recentPaidBookings = Booking::whereIn('status', $paidStatuses)
            ->where('bookings.created_at', '>=', $sixMonthsAgo)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->select('bookings.created_at', 'services.price')
            ->get();

        $monthlyTrend = $recentPaidBookings
            ->groupBy(fn($row) => Carbon::parse($row->created_at)->format('Y-m'))
            ->map(fn($rows) => $rows->sum('price'))
            ->sortKeys();

        // Top earning business nodes
        $topBusinesses = Business::all()->map(function ($b) use ($paidStatuses) {
            $revenue = (float) Booking::where('bookings.business_id', $b->id)
                ->whereIn('status', $paidStatuses)
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->sum('services.price');
            return [
                'id' => $b->id,
                'name' => $b->name,
                'slug' => $b->slug,
                'revenue' => $revenue,
            ];
        })
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        return Inertia::render('Admin/SuperAdmin/Financials', [
            'summary' => [
                'gross_revenue' => number_format($grossRevenue, 2),
                'platform_yield' => number_format($grossRevenue * $commissionRate, 2),
                'commission_rate' => $commissionRate * 100,
                'total_volume' => $totalVolume,
                'paid_volume' => $paidVolume,
                'avg_booking_value' => $paidVolume > 0 ? number_format($grossRevenue / $paidVolume, 2) : '0.00',
            ],
            'statusBreakdown' => $statusBreakdown,
            'chartData' => [
                'labels' => $monthlyTrend->keys(),
                'revenue' => $monthlyTrend->values(),
            ],
            'topBusinesses' => $topBusinesses,
        ]);
    }

    /**
     * Global Security Audit Ledger.
     *
     * NOTE: There is no dedicated audit_logs table yet, so this derives a
     * chronological activity feed from existing timestamped records
     * (new businesses, booking lifecycle events). Swap this out for a real
     * audit trail table if formal compliance logging is needed later.
     */
    public function audit(): Response
    {
        $businessEvents = Business::with('user')->latest()->take(15)->get()->map(fn($b) => [
            'type' => 'business.created',
            'label' => 'New business node registered',
            'detail' => $b->name,
            'actor' => $b->user->name ?? 'Unknown Principal',
            'timestamp' => $b->created_at,
        ]);

        $bookingEvents = Booking::with(['business', 'service'])
            ->latest()
            ->take(25)
            ->get()
            ->map(fn($bk) => [
                'type' => 'booking.' . $bk->status,
                'label' => match ($bk->status) {
                    'pending' => 'Booking created',
                    'confirmed' => 'Booking confirmed',
                    'canceled' => 'Booking canceled',
                    'completed' => 'Booking completed',
                    default => 'Booking updated',
                },
                'detail' => ($bk->service->name ?? 'Service') . ' @ ' . ($bk->business->name ?? 'Unknown node'),
                'actor' => $bk->business->name ?? 'Unknown node',
                'timestamp' => $bk->updated_at,
            ]);

        $events = $businessEvents->concat($bookingEvents)
            ->sortByDesc('timestamp')
            ->take(30)
            ->values()
            ->map(fn($e) => array_merge($e, [
                'timestamp' => $e['timestamp']?->format('M d, Y \a\t h:i A'),
            ]));

        return Inertia::render('Admin/SuperAdmin/AuditLogs', [
            'events' => $events,
        ]);
    }

    /**
     * System-Wide Configuration Hub.
     * Read-only snapshot of the current runtime configuration
     * (app, mail, queue, storage, session drivers, versions).
     */
    public function config(): Response
    {
        return Inertia::render('Admin/SuperAdmin/Config', [
            'runtime' => [
                'app_name' => config('app.name'),
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'app_url' => config('app.url'),
                'app_timezone' => config('app.timezone'),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
            ],
            'services' => [
                'database_driver' => config('database.default'),
                'mail_driver' => config('mail.default'),
                'mail_from' => config('mail.from.address'),
                'queue_connection' => config('queue.default'),
                'session_driver' => config('session.driver'),
                'filesystem_disk' => config('filesystems.default'),
                'cache_store' => config('cache.default'),
            ],
        ]);
    }
}