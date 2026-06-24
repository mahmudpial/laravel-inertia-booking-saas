<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BusinessSettingsController extends Controller
{
    /**
     * Render the administrative business settings customization panel.
     */
    public function edit()
    {
        $business = Auth::user()->business;

        return Inertia::render('Admin/Settings/Edit', [
            'business' => $business
        ]);
    }

    /**
     * Handle incoming multi-tenant operational configuration updates.
     */
    public function update(Request $request)
    {
        $business = Auth::user()->business;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Process profile logo upload securely if present
        if ($request->hasFile('logo')) {
            if ($business->logo) {
                Storage::disk('public')->delete($business->logo);
            }
            $validated['logo'] = $request->file('logo')->store('assets/logos', 'public');
        }

        // Process display banner image upload securely if present
        if ($request->hasFile('banner')) {
            if ($business->banner) {
                Storage::disk('public')->delete($business->banner);
            }
            $validated['banner'] = $request->file('banner')->store('assets/banners', 'public');
        }

        $business->update($validated);

        return redirect()->back()->with('success', 'Business operational profile updated successfully!');
    }
}