<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of the operational units with their enhancement modules.
     */
    public function index(): Response
    {
        $user = Auth::user();
        $business = $user->business;

        // Pro Update: Eager load addons to prevent N+1 query issues
        $services = $business
            ? $business->services()->with('addons')->latest()->get()
            : collect();

        return Inertia::render('Admin/Services/Index', [
            'services' => $services
        ]);
    }

    /**
     * Deploy a new service SKU and its associated add-on intelligence.
     */
    public function store(Request $request): RedirectResponse
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->back()->withErrors(['business' => 'Operational profile not found. Complete onboarding first.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            // Add-on Validation Logic
            'addons' => ['nullable', 'array'],
            'addons.*.name' => ['required', 'string', 'max:255'],
            'addons.*.price' => ['required', 'numeric', 'min:0'],
            'addons.*.duration_minutes' => ['required', 'integer', 'min:0'],
        ]);

        return DB::transaction(function () use ($business, $validated) {
            // 1. Create the primary Service SKU
            $service = $business->services()->create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'duration_minutes' => $validated['duration_minutes'],
                'description' => $validated['description'],
                'is_active' => true,
            ]);

            // 2. Map and store Enhancement Packets (Add-ons)
            if (!empty($validated['addons'])) {
                foreach ($validated['addons'] as $addonData) {
                    $service->addons()->create($addonData);
                }
            }

            return redirect()->back()->with('success', 'SKU and enhancement modules deployed successfully.');
        });
    }

    /**
     * Update an existing SKU configuration and re-quantize add-ons.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $business = Auth::user()->business;

        // Security Protocol: Ownership Handshake
        if (!$business || $service->business_id !== $business->id) {
            abort(403, 'Unauthorized system access attempt.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'addons' => ['nullable', 'array'],
        ]);

        return DB::transaction(function () use ($validated, $service) {
            // 1. Update the parent record
            $service->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'duration_minutes' => $validated['duration_minutes'],
                'description' => $validated['description'],
            ]);

            // 2. Synchronize Add-ons (Delete old, Re-create new for consistency)
            $service->addons()->delete();
            if (!empty($validated['addons'])) {
                foreach ($validated['addons'] as $addonData) {
                    $service->addons()->create([
                        'name' => $addonData['name'],
                        'price' => $addonData['price'],
                        'duration_minutes' => $addonData['duration_minutes'],
                    ]);
                }
            }

            return redirect()->back()->with('success', 'System parameters synchronized.');
        });
    }

    /**
     * Terminate the specified SKU and all linked modules.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $business = Auth::user()->business;

        if (!$business || $service->business_id !== $business->id) {
            abort(403, 'Unauthorized destruction request.');
        }

        // Add-ons will be deleted automatically via Database Cascade
        $service->delete();

        return redirect()->back()->with('success', 'Operational SKU purged from system.');
    }
}