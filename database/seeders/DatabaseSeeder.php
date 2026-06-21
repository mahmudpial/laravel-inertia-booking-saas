<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Availability;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);



        // আমরা যে business_id = 1 ম্যানুয়ালি তৈরি করেছিলাম, সেটির জন্য ৭ দিনের শিডিউল তৈরি করছি
        for ($day = 0; $day <= 6; $day++) {
            Availability::create([
                'business_id' => 1,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'is_open' => $day === 5 ? false : true, // ধরি, ৫ নম্বর দিন অর্থাৎ শুক্রবার (Friday) বন্ধ থাকবে
            ]);
        }
    }
}
