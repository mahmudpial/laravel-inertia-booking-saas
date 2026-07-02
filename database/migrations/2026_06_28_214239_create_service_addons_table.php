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
        // ১. অ্যাড-অন টেবিল (যেখানে এক্সট্রা সার্ভিস থাকবে)
        Schema::create('service_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('duration_minutes')->default(0);
            $table->timestamps();
        });

        // ২. পিভট টেবিল (বুকিং এর সাথে কোন কোন অ্যাড-অন নেয়া হয়েছে তা সেভ করতে)
        Schema::create('booking_service_addon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_addon_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_addons');
    }
};
