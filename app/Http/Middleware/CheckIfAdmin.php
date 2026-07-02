<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'owner' && auth()->user()->role !== 'super_admin') {
            return redirect()->route('customer.dashboard')->with('error', 'Unauthorized access.');
        }
        return $next($request);
    }
}
