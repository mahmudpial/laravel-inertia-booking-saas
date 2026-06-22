<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    // ১. সার্ভিসের লিস্ট দেখানো
    public function index()
    {
        $user = auth()->user();
        $business = $user ? $user->business : null;

        $services = $business ? $business->services()->latest()->get() : [];

        return Inertia::render('Admin/Services/Index', [
            'services' => $services
        ]);
    }

    // ২. নতুন সার্ভিস ডাটাবেসে সেভ করা
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1', // 👈 'duration' পরিবর্তন করে 'duration_minutes' করা হলো
            'description' => 'nullable|string',
        ]);

        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->back()->withErrors([
                'name' => 'You do not have a registered business! Please setup your business first.'
            ]);
        }

        // ব্যবসার আন্ডারে সার্ভিস তৈরি
        $business->services()->create($validated);

        return redirect()->back()->with('message', 'Service created successfully!');
    }

    // ৩. বিদ্যমান সার্ভিস আপডেট করা (🔧 এডিট ফিচার)
    public function update(Request $request, Service $service)
    {
        if ($service->business_id !== auth()->user()->business->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1', // 👈 'duration' পরিবর্তন করে 'duration_minutes' করা হলো
            'description' => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect()->back()->with('message', 'Service updated successfully!');
    }

    // ৪. সার্ভিস ডিলিট করা (🗑️ ডিলিট ফিচার)
    public function destroy(Service $service)
    {
        if ($service->business_id !== auth()->user()->business->id) {
            abort(403, 'Unauthorized action.');
        }

        $service->delete();

        return redirect()->back()->with('message', 'Service deleted successfully!');
    }
}