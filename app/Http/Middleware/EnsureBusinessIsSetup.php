<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessIsSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Protocol: Only force onboarding if the user is an 'owner' and has no business.
        // If they are a 'customer', they should be allowed to proceed to their dashboard.
        if (auth()->check() && $user->role === 'owner' && !$user->business) {
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.index');
            }
        }

        return $next($request);
    }
}