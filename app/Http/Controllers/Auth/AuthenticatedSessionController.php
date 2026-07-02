<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class AuthenticatedSessionController
 * 
 * This controller handles the core authentication lifecycle of the SmartBooking System.
 * It manages identity verification, session synchronization, and role-based tactical routing.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the identity verification (Login) view.
     * 
     * Renders the premium Auth/Login component via the Inertia engine,
     * passing necessary session status and password recovery availability.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request (Session Initialization).
     * 
     * Workflow:
     * 1. Validate credentials via the LoginRequest security layer.
     * 2. Regenerate the session to mitigate session fixation vulnerabilities.
     * 3. Execute Role-Based Redirection:
     *    - Administrative Nodes (Owner/SuperAdmin) -> Management Dashboard.
     *    - Client Nodes (Customers) -> Personal Agenda Portal.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Step 1: Execute the secure handshake and verify user credentials.
        $request->authenticate();

        // Step 2: Refresh the session ID for enhanced security integrity.
        $request->session()->regenerate();

        // Step 3: Identity-Aware Routing.
        $user = $request->user();

        /**
         * REDIRECTION LOGIC:
         * We distinguish between 'Administrative' and 'Client' nodes.
         * Owners and Super Admins require immediate access to the Command Center (Dashboard).
         * Regular customers are routed to their personal operational view (Agenda).
         */
        if ($user->role === 'owner' || $user->role === 'super_admin') {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Default routing for standard customer nodes.
        return redirect()->intended(route('customer.dashboard', absolute: false));
    }

    /**
     * Terminate the active authenticated session (Logout).
     * 
     * Workflow:
     * 1. Log out the user via the web guard.
     * 2. Invalidate the existing session data.
     * 3. Regenerate the CSRF token to prevent cross-site request forgery on the next session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Step 1: Disconnect the user identity from the web guard.
        Auth::guard('web')->logout();

        // Step 2: Purge current session intelligence.
        $request->session()->invalidate();

        // Step 3: Cycle the security token for the next authentication attempt.
        $request->session()->regenerateToken();

        // Redirect to the public infrastructure landing page.
        return redirect('/');
    }
}