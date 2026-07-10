<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display the personnel roster index.
     * TenantScope safely auto-injects business_id filtering context.
     */
    public function index()
    {
        if (!Auth::user()->business_id) {
            return redirect()->route('onboarding.index')->with('error', 'Business profile synchronization required.');
        }

        // Clean: No manual business relationship queries needed. TenantScope secures this ecosystem.
        $staff = Staff::latest()->get();

        return Inertia::render('Admin/Staff/Index', [
            'staff' => $staff
        ]);
    }

    /**
     * Authorize and deploy a new specialist to the roster.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->business_id) {
            return redirect()->back()->withErrors(['business' => 'Operational node not found.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
        ]);

        // Clean: BelongsToTenant trait automatically injects business_id on creation
        Staff::create($validated);

        return redirect()->back()->with('message', 'Specialist deployed to roster successfully.');
    }

    /**
     * Terminate specialist access from the system.
     * Route Model Binding automatically enforces TenantScope; cross-tenant lookup returns 404.
     */
    public function destroy(Staff $staff)
    {
        // Secure: TenantScope guarantees a rogue tenant cannot delete another tenant's staff model via URL tampering
        $staff->delete();

        return redirect()->back()->with('message', 'Specialist access revoked.');
    }
}