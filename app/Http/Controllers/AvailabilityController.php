<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    // ৭ দিনের শিডিউল লিস্ট অ্যাডমিন প্যানেলে দেখানো
    public function index()
    {
        $business = auth()->user()->business;

        // ব্যবসার সমস্ত কাজের সময় দিন অনুযায়ী সিরিয়াল করে নিয়ে আসা (০ = রবিবার, ১ = সোমবার...)
        $availabilities = $business ? $business->availabilities()->orderBy('day_of_week')->get() : [];

        return Inertia::render('Admin/Availability', [
            'availabilities' => $availabilities
        ]);
    }

    // শিডিউল আপডেট বা সেভ করা
    public function update(Request $request)
    {
        $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.id' => 'required|exists:availabilities,id',
            'availabilities.*.start_time' => 'required',
            'availabilities.*.end_time' => 'required',
            'availabilities.*.is_open' => 'required|boolean',
        ]);

        $business = auth()->user()->business;

        foreach ($request->availabilities as $data) {
            // সিকিউরিটি চেক: নিশ্চিত করা হচ্ছে এই শিডিউল আইডিটি সত্যিই এই অ্যাডমিনের ব্যবসার কিনা
            $availability = $business->availabilities()->findOrFail($data['id']);

            $availability->update([
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'is_open' => $data['is_open'],
            ]);
        }

        return redirect()->back()->with('message', 'Business hours updated successfully!');
    }
}