<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        // ১. নিশ্চিত করা হচ্ছে ইউজার লগইন অবস্থায় আছে কিনা
        $user = auth()->user();

        // ২. লগইন করা ইউজারের ব্যবসা খুঁজে বের করা
        $business = $user ? $user->business : null;

        // যদি ইউজারের কোনো ব্যবসা সেটআপ করা না থাকে, তবে খালি অ্যারে পাঠানো
        $services = $business ? $business->services()->latest()->get() : [];

        return Inertia::render('Services/Index', [
            'services' => $services
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // ১. লগইন করা ইউজারের ব্যবসা চেক করা
        $business = auth()->user()->business;

        // ২. যদি ডাটাবেসে এই ইউজারের কোনো ব্যবসা না থাকে, তবে ক্র্যাশ না করে এরর ব্যাক করবে
        if (!$business) {
            return redirect()->back()->withErrors([
                'name' => 'You do not have a registered business! Please assign a business to your user first.'
            ]);
        }

        // ৩. ব্যবসা থাকলে কেবল তখনই তার আন্ডারে সার্ভিসটি তৈরি হবে
        $business->services()->create($validated);

        return redirect()->back()->with('message', 'Service created successfully!');
    }
}