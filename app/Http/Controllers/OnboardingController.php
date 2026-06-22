<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    // অনবোর্ডিং ফরম দেখানো
    public function index()
    {
        // ইউজারের অলরেডি বিজনেস থাকলে ড্যাশবোর্ডে পাঠিয়ে দেবে
        if (auth()->user()->business) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Onboarding');
    }

    // বিজনেস ও ডিফল্ট শিডিউল ডাটাবেসে সেভ করা
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|max:255|unique:businesses,slug',
        ]);

        // ১. বিজনেস তৈরি করা
        $business = Business::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        // ২. এই বিজনেসের জন্য অটোমেটিক ৭ দিনের ডিফল্ট শিডিউল তৈরি করা (রবি থেকে শনি)
        for ($i = 0; $i <= 6; $i++) {
            Availability::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'is_open' => true,
            ]);
        }

        return redirect()->route('dashboard')->with('message', 'Business setup completed successfully!');
    }
}