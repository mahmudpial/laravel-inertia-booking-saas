<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

/**
 * Class CustomerDashboardController
 * 
 * This controller serves as the primary intelligence node for the Client Portal (My Agenda).
 * It manages the retrieval of synchronized booking packets and handles the 
 * termination protocols for active customer reservations.
 */
class CustomerDashboardController extends Controller
{
    /**
     * Display the Client's Personal Agenda.
     * 
     * Scenario:
     * The system identifies the authenticated client node and retrieves all associated
     * reservation packets. It eager-loads business identities, service SKUs, and 
     * assigned specialists to provide a high-density operational view. 
     * 
     * Workflow:
     * 1. Extract session packets via Eloquent relations.
     * 2. Map raw database records into sanitized tactical data objects.
     * 3. Calculate operational stats (Active vs. Total throughput).
     */
    public function index(): Response
    {
        $user = auth()->user();

        // Retrieve and transform historical and upcoming session packets.
        $bookings = $user->bookings()
            ->with(['business', 'service', 'staff'])
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'business_name' => $booking->business->name,
                    'business_slug' => $booking->business->slug, // Required for Re-booking UI
                    'service_name' => $booking->service->name,
                    'staff_name' => $booking->staff->name ?? 'Any Specialist',
                    'date' => Carbon::parse($booking->booking_date)->format('M d, Y'),
                    'time' => Carbon::parse($booking->start_time)->format('h:i A'),
                    'status' => ucfirst($booking->status),
                    // Quantize if the session is still active or in the future
                    'is_upcoming' => Carbon::parse($booking->booking_date)->isFuture() ||
                        Carbon::parse($booking->booking_date)->isToday(),
                ];
            });

        return Inertia::render('Customer/Dashboard', [
            'bookings' => $bookings,
            'stats' => [
                'total' => $bookings->count(),
                'upcoming' => $bookings->where('is_upcoming', true)->count(),
            ]
        ]);
    }

    /**
     * Terminate an active reservation packet.
     * 
     * Scenario:
     * A client requests the immediate cancellation of a scheduled session. 
     * The system must verify ownership of the packet and ensure it is not already 
     * in a terminated state before executing the status transition.
     * 
     * Workflow:
     * 1. Fetch record strictly through the user's relationship for security (Ownership Handshake).
     * 2. Validate that the packet is not already marked as 'canceled'.
     * 3. Transition the status to 'canceled' to re-quantize specialist availability.
     */
    public function cancel($id): RedirectResponse
    {
        $user = auth()->user();

        // Secure Handshake: Ensure the node belongs to the authenticated user.
        $booking = $user->bookings()
            ->where('status', '!=', 'canceled')
            ->findOrFail($id);

        // Execute Termination Protocol
        $booking->update(['status' => 'canceled']);

        /**
         * CTO Note: In a production environment, this is where we would trigger 
         * an automated SMTP dispatch to the business admin to notify them of 
         * the newly available time-slot.
         */

        return redirect()->back()->with('message', 'Reservation Terminated Successfully.');
    }
}