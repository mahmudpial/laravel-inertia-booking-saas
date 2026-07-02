<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding form.
     */
    public function index(): Response|RedirectResponse
    {
        // ইউজারের অলরেডি বিজনেস থাকলে ড্যাশবোর্ডে পাঠিয়ে দিন
        if (auth()->user()->business) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Onboarding');
    }

    /**
     * Store business and initialize default availability schedules.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:businesses,slug'],
        ]);

        // business and default availability schedules make in a transaction
        DB::transaction(function () use ($request) {
            $business = Business::create([
                'user_id' => $request->user()->id, // default user id set
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
            ]);

            // Update user role to owner
            $user = $request->user();
            $user->role = 'owner';
            $user->save();

            // default availability schedules for each day of the week (0-6)
            for ($i = 0; $i <= 6; $i++) {
                $business->availabilities()->create([
                    'day_of_week' => $i,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_open' => true,
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Business profile and schedule initialized successfully!');
    }
}