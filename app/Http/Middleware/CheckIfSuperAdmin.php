<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfSuperAdmin
{
    /**
     * Handle an incoming request.
     * 
     * Security Handshake: Only users with the 'super_admin' role 
     * are granted access to the global oversight infrastructure.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Is the user logged in?
        // 2. Is the user's role 'super_admin'?
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return $next($request);
        }

        // If not authorized, redirect to dashboard with error message
        return redirect()->route('dashboard')->with('error', 'Access Denied: Sovereign Node Authorization Required.');
    }
}