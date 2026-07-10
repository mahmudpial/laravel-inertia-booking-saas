<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Booking;
use App\Models\User;
use App\Models\Scopes\TenantScope;
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
 * configurations, explicitly bypassing individual tenant constraints.
 */
class SuperAdminController extends Controller
{
    /**
     * Display the Sovereign Oversight Dashboard (Global Hub).
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

        // Platform-wide Gross Revenue - Explicitly bypassing TenantScope for sovereign macro overview
        $globalRevenue = Booking::withoutGlobalScope(TenantScope::class)
            ->whereIn('bookings.status', ['confirmed', 'completed'])
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        // 02. FINANCIAL TRAJECTORY (30-Day Real-Time Cycle)
        $revenueTrend = Booking::withoutGlobalScope(TenantScope::class)
            ->whereIn('bookings.status', ['confirmed', 'completed'])
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
        // Explicitly bypassing sub-query scopes on target tenant metrics
        $recentEntities = Business::with('user')
            ->withCount([
                'bookings' => function ($query) {
                    $query->withoutGlobalScope(TenantScope::class);
                }
            ])
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
                'mrr' => number_format($globalRevenue / 12, 2),
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
     */
    public function entities()
    {
        // Safe cross-tenant count aggregation for the platform grid
        $entities = Business::with('user')
            ->withCount([
                'bookings' => function ($query) {
                    $query->withoutGlobalScope(TenantScope::class);
                },
                'services' => function ($query) {
                    $query->withoutGlobalScope(TenantScope::class);
                }
            ])
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
     */
    public function financials(): Response
    {
        $commissionRate = 0.12;
        $paidStatuses = ['confirmed', 'completed'];

        // Global metrics need absolute query contexts
        $grossRevenue = (float) Booking::withoutGlobalScope(TenantScope::class)
            ->whereIn('bookings.status', $paidStatuses)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->sum('services.price');

        $totalVolume = Booking::withoutGlobalScope(TenantScope::class)->count();
        $paidVolume = Booking::withoutGlobalScope(TenantScope::class)->whereIn('status', $paidStatuses)->count();

        $statusBreakdown = Booking::withoutGlobalScope(TenantScope::class)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();

        $recentPaidBookings = Booking::withoutGlobalScope(TenantScope::class)
            ->whereIn('bookings.status', $paidStatuses)
            ->where('bookings.created_at', '>=', $sixMonthsAgo)
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->select('bookings.created_at', 'services.price')
            ->get();

        $monthlyTrend = $recentPaidBookings
            ->groupBy(fn($row) => Carbon::parse($row->created_at)->format('Y-m'))
            ->map(fn($rows) => $rows->sum('price'))
            ->sortKeys();

        // Top earning business nodes mapping with isolated query bypass
        $topBusinesses = Business::all()->map(function ($b) use ($paidStatuses) {
            $revenue = (float) Booking::withoutGlobalScope(TenantScope::class)
                ->where('bookings.business_id', $b->id)
                ->whereIn('bookings.status', $paidStatuses)
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

        $bookingEvents = Booking::withoutGlobalScope(TenantScope::class)
            ->with([
                'business',
                'service' => function ($query) {
                    $query->withoutGlobalScope(TenantScope::class);
                }
            ])
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