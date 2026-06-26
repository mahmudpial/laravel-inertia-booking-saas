<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of the business services.
     */
    public function index(): Response
    {
        $user = Auth::user();

        // বিজনেস না থাকলে একটি empty collection পাঠান, এতে অ্যাপ ক্র্যাশ করবে না
        $services = ($user && $user->business) ? $user->business->services()->latest()->get() : collect();

        return Inertia::render('Admin/Services/Index', [
            'services' => $services
        ]);
    }

    /**
     * Persist a new service entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->back()->withErrors(['business' => 'Business profile not found. Please complete your onboarding.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['is_active'] = true;
        $business->services()->create($validated);

        return redirect()->back()->with('success', 'Service created successfully.');
    }

    /**
     * Update an existing service record.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $business = Auth::user()->business;

        // নিরাপত্তা চেক: বিজনেস এক্সিস্ট করে কি না এবং সার্ভিসটি ওই বিজনেসের কি না
        if (!$business || $service->business_id !== $business->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $service->update($validated);

        return redirect()->back()->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $business = Auth::user()->business;

        // নিরাপত্তা চেক
        if (!$business || $service->business_id !== $business->id) {
            abort(403, 'Unauthorized access.');
        }

        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }
}