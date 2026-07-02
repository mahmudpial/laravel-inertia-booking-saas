<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    /**
     * Display the personnel roster index.
     */
    public function index()
    {
        // ১. বিজনেসটি আগে একটি ভেরিয়েবলে নিন
        $business = auth()->user()->business;

        // ২. যদি বিজনেস না থাকে তবে অনবোর্ডিং-এ রিডাইরেক্ট করুন (Fail-safe logic)
        if (!$business) {
            return redirect()->route('onboarding.index')->with('error', 'Business profile synchronization required.');
        }

        // ৩. বিজনেস থাকলে স্টাফ ডাটা আনুন
        $staff = $business->staff()->latest()->get();

        return Inertia::render('Admin/Staff/Index', [
            'staff' => $staff
        ]);
    }

    /**
     * Authorize and deploy a new specialist to the roster.
     */
    public function store(Request $request)
    {
        $business = auth()->user()->business;

        if (!$business) {
            return redirect()->back()->withErrors(['business' => 'Operational node not found.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        // বিজনেসের আন্ডারে স্টাফ তৈরি করা
        $business->staff()->create($validated);

        return redirect()->back()->with('message', 'Specialist deployed to roster successfully.');
    }

    /**
     * Terminate specialist access from the system.
     */
    public function destroy(Staff $staff)
    {
        $business = auth()->user()->business;

        // নিরাপত্তা চেক: স্টাফটি কি এই ইউজারের বিজনেসের?
        if (!$business || $staff->business_id !== $business->id) {
            abort(403, 'Unauthorized access request.');
        }

        $staff->delete();

        return redirect()->back()->with('message', 'Specialist access revoked.');
    }
}