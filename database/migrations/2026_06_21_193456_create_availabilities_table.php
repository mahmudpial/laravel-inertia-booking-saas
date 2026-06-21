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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');

            // সপ্তাহের দিন বোঝাতে (০ = রবিবার, ১ = সোমবার ... ৬ = শনিবার)
            $table->unsignedTinyInteger('day_of_week');

            $table->time('start_time')->nullable(); // কখন দোকান খোলে (যেমন: 09:00:00)
            $table->time('end_time')->nullable();   // কখন দোকান বন্ধ হয় (যেমন: 18:00:00)
            $table->boolean('is_open')->default(true); // ওই দিন দোকান খোলা নাকি বন্ধ (যেমন: শুক্রবারে বন্ধ থাকতে পারে)

            $table->timestamps();

            // একই ব্যবসার এক দিনের একাধিক রেকর্ড যেন না হয়, তাই ইউনিক ইনডেক্স
            $table->unique(['business_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
