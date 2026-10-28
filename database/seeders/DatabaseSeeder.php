<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\Service;
use App\Models\ServiceAddon;
use App\Models\Staff;
use App\Models\Booking;
use App\Models\Availability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin
        $superAdmin = User::create([
            'name' => 'Platform Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // 2. Business Owner
        $owner = User::create([
            'name' => 'Alex Morgan',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'email_verified_at' => now(),
        ]);

        // 3. Business (Tenant)
        $business = Business::create([
            'user_id' => $owner->id,
            'name' => 'Elite Studio & Spa',
            'slug' => 'elite-studio',
            'description' => 'Luxury haircuts, styling, beard grooming, and therapeutic spa experiences.',
            'address' => '742 Evergreen Terrace, Suite 100',
            'phone' => '+1 (555) 234-5678',
            'status' => 'active',
        ]);

        $owner->update(['business_id' => $business->id]);

        // 4. Availability (7 Days)
        for ($day = 0; $day <= 6; $day++) {
            Availability::create([
                'business_id' => $business->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'is_open' => $day !== 0, // Closed on Sundays
            ]);
        }

        // 5. Staff
        $staff1 = Staff::create([
            'business_id' => $business->id,
            'name' => 'Sarah Jenkins',
            'email' => 'sarah@elitestudio.test',
            'role' => 'Master Stylist',
            'is_active' => true,
        ]);

        $staff2 = Staff::create([
            'business_id' => $business->id,
            'name' => 'Michael Chen',
            'email' => 'michael@elitestudio.test',
            'role' => 'Senior Colorist',
            'is_active' => true,
        ]);

        // 6. Services & Add-ons
        $service1 = Service::create([
            'business_id' => $business->id,
            'name' => 'Executive Haircut & Styling',
            'description' => 'Precision scissor cut with refreshing hot towel treatment and styling.',
            'duration_minutes' => 45,
            'price' => 45.00,
            'is_active' => true,
        ]);

        ServiceAddon::create([
            'service_id' => $service1->id,
            'name' => 'Hair Wash & Scalp Massage',
            'price' => 15.00,
            'duration_minutes' => 15,
        ]);

        $service2 = Service::create([
            'business_id' => $business->id,
            'name' => 'Deluxe Beard Grooming',
            'description' => 'Hot foam straight-razor shave with beard oil conditioning.',
            'duration_minutes' => 30,
            'price' => 30.00,
            'is_active' => true,
        ]);

        $service3 = Service::create([
            'business_id' => $business->id,
            'name' => 'Full Scalp & Therapy Spa',
            'description' => 'Rejuvenating hair restoration treatment with aromatherapy.',
            'duration_minutes' => 60,
            'price' => 75.00,
            'is_active' => true,
        ]);

        // 7. Customer
        $customer = User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // 8. Sample Bookings
        Booking::create([
            'business_id' => $business->id,
            'user_id' => $customer->id,
            'service_id' => $service1->id,
            'staff_id' => $staff1->id,
            'booking_date' => Carbon::tomorrow()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => 'confirmed',
            'notes' => 'First time visitor, preferred short sides.',
        ]);

        Booking::create([
            'business_id' => $business->id,
            'user_id' => $customer->id,
            'service_id' => $service2->id,
            'staff_id' => $staff2->id,
            'booking_date' => Carbon::yesterday()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '14:30:00',
            'status' => 'completed',
            'notes' => 'Completed smoothly.',
        ]);
    }
}
