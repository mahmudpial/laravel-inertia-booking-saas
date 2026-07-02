<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * এই সেকশনে আমরা নতুন টেবিল এবং কলাম তৈরি করি।
     */
    public function up(): void
    {
        // ১. স্টাফ টেবিল তৈরি করা
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            // কোন বিজনেসের আন্ডারে এই স্টাফ কাজ করে
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('role')->nullable(); // যেমন: Senior Specialist, Consultant
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ২. বুকিং টেবিলে স্টাফ আইডি যুক্ত করা (Relational Integrity)
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'staff_id')) {
                $table->foreignId('staff_id')
                    ->nullable()
                    ->after('service_id')
                    ->constrained()
                    ->onDelete('set null'); // স্টাফ ডিলিট হয়ে গেলেও বুকিং রেকর্ড থেকে যাবে
            }
        });
    }

    /**
     * Reverse the migrations.
     * রোলব্যাক করার সময় এই মেথডটি ডাটাবেজ আগের অবস্থায় ফিরিয়ে নেয়।
     */
    public function down(): void
    {
        // ১. আগে বুকিং টেবিলের ফরেন কি এবং কলামটি রিমুভ করতে হবে
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'staff_id')) {
                $table->dropForeign(['staff_id']);
                $table->dropColumn('staff_id');
            }
        });

        // ২. এরপর স্টাফ টেবিলটি ড্রপ করতে হবে
        Schema::dropIfExists('staff');
    }
};