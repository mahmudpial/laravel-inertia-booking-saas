<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // কাস্টমার
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // কোন সার্ভিস

            $table->date('booking_date'); // কোন দিন (যেমন: 2026-06-25)
            $table->time('start_time');   // কখন শুরু (যেমন: 10:00:00)
            $table->time('end_time');     // কখন শেষ (যেমন: 10:30:00 - এটি লারাভেল নিজে ক্যালকুলেট করবে)

            // বুকিং স্ট্যাটাস ম্যানেজমেন্ট
            $table->string('status')->default('pending'); // pending, confirmed, canceled, completed

            $table->text('notes')->nullable(); // কাস্টমার যদি কোনো বিশেষ নোট লিখতে চায়
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
