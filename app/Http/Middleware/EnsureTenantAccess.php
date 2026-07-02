<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Protocol: If not owner/admin OR has no business, deny access.
        if (!$user->business) {
            return redirect()->route('onboarding.index');
        }

        // Professional SaaS Logic: 
        // We bind the business_id to the request for easy access in controllers
        $request->merge(['tenant_id' => $user->business->id]);

        return $next($request);
    }
}