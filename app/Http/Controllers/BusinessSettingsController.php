<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BusinessSettingsController extends Controller
{
    /**
     * Render the settings page.
     * এই ফাংশনটি না থাকার কারণেই আপনার এররটি আসছিল।
     */
    public function edit()
    {
        $business = Auth::user()->business;

        return Inertia::render('Admin/Settings/Edit', [
            'business' => $business
        ]);
    }

    /**
     * Handle the update logic including files and description.
     */
    public function update(Request $request)
    {
        $business = Auth::user()->business;

        // ভ্যালিডেশন (Description এবং অন্যান্য ফিল্ড নিশ্চিত করা)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // ১. লোগো আপলোড লজিক
        if ($request->hasFile('logo')) {
            // আগের লোগো থাকলে ডিলিট করা (অপশনাল কিন্তু ক্লিন কোডিং)
            if ($business->logo) {
                Storage::disk('public')->delete($business->logo);
            }
            $validated['logo'] = $request->file('logo')->store('assets/logos', 'public');
        }

        // ২. ব্যানার আপলোড লজিক
        if ($request->hasFile('banner')) {
            if ($business->banner) {
                Storage::disk('public')->delete($business->banner);
            }
            $validated['banner'] = $request->file('banner')->store('assets/banners', 'public');
        }

        // ৩. ডাটাবেজ আপডেট
        $business->update($validated);

        return redirect()->back()->with('success', 'Operational profile synchronized successfully.');
    }
}