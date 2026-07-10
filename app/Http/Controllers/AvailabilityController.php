<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display the weekly schedule index.
     * TenantScope safely auto-injects business_id filtering context.
     */
    public function index()
    {
        if (!Auth::user()->business_id) {
            return redirect()->route('onboarding.index')->with('error', 'Business profile synchronization required.');
        }

        // Clean: No manual business relationship queries needed. TenantScope secures this ecosystem.
        $availabilities = Availability::orderBy('day_of_week')->get();

        return Inertia::render('Admin/Availability', [
            'availabilities' => $availabilities
        ]);
    }

    /**
     * Mass update schedule records.
     */
    public function update(Request $request)
    {
        $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.id' => 'required|exists:availabilities,id',
            'availabilities.*.start_time' => 'required',
            'availabilities.*.end_time' => 'required',
            'availabilities.*.is_open' => 'required|boolean',
        ]);

        // Secure Performance Optimization: Looping updates scoped inherently by TenantScope
        foreach ($request->availabilities as $data) {
            // Secure: TenantScope guarantees a tenant can only mutate their own records.
            // If an invalid or rogue ID is passed, findOrFail will instantly throw a 404.
            $availability = Availability::findOrFail($data['id']);

            $availability->update([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'is_open' => $data['is_open'],
            ]);
        }

        return redirect()->back()->with('message', 'Business hours updated successfully!');
    }
}