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
     * TenantScope safely auto-injects business_id filtering context.
     */
    public function index(): Response
    {
        // Clean: No manual business relationship queries needed. TenantScope secures this ecosystem.
        $services = Service::with('addons')->latest()->get();

        return Inertia::render('Admin/Services/Index', [
            'services' => $services
        ]);
    }

    /**
     * Deploy a new service SKU and its associated add-on intelligence.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->business_id) {
            return redirect()->back()->withErrors(['business' => 'Operational profile not found. Complete onboarding first.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'addons' => ['nullable', 'array'],
            'addons.*.name' => ['required', 'string', 'max:255'],
            'addons.*.price' => ['required', 'numeric', 'min:0'],
            'addons.*.duration_minutes' => ['required', 'integer', 'min:0'],
        ]);

        return DB::transaction(function () use ($validated) {
            // Clean: BelongsToTenant trait automatically injects business_id on creation
            $service = Service::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'duration_minutes' => $validated['duration_minutes'],
                'description' => $validated['description'],
                'is_active' => true,
            ]);

            // Map and store Enhancement Packets (Add-ons)
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
     * Route Model Binding automatically enforces TenantScope; cross-tenant lookup returns 404.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'addons' => ['nullable', 'array'],
        ]);

        return DB::transaction(function () use ($validated, $service) {
            // Secure: Automatically scoped to the active tenant execution path
            $service->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'duration_minutes' => $validated['duration_minutes'],
                'description' => $validated['description'],
            ]);

            // Synchronize Add-ons (Delete old, Re-create new for consistency)
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
        // Secure: TenantScope guarantees a rogue tenant cannot delete another tenant's resource model via routing hacks
        $service->delete();

        return redirect()->back()->with('success', 'Operational SKU purged from system.');
    }
}