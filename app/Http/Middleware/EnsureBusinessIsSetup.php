<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessIsSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        // ইউজার লগইন করা আছে কিন্তু তার কোনো বিজনেস ক্রিয়েট করা নেই
        if (auth()->check() && !auth()->user()->business) {
            // যদি সে অলরেডি অনবোর্ডিং পেজে যাওয়ার রাউটে না থাকে, তবে তাকে রিডাইরেক্ট করো
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.index');
            }
        }

        return $next($request);
    }
}