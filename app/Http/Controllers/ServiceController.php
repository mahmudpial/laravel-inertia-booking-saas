<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    // সব সার্ভিসের লিস্ট দেখানোর জন্য
    public function index()
    {
        // সাময়িকভাবে আমরা business_id = 1 ধরে নিচ্ছি, পরে আমরা এটি ডাইনামিক করব
        $services = Service::where('business_id', 1)->latest()->get();

        return Inertia::render('Services/Index', [
            'services' => $services
        ]);
    }

    // নতুন সার্ভিস ডাটাবেসে সেভ করার জন্য
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // সাময়িকভাবে business_id = 1 দিয়ে ডাটা সেভ করছি
        Service::create([
            'business_id' => 1,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'price' => $validated['price'],
        ]);

        return redirect()->back()->with('message', 'Service created successfully!');
    }
}