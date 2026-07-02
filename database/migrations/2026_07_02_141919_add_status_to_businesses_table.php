<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // ডিফল্ট স্ট্যাটাস হিসেবে 'active' সেট করা হয়েছে
            $table->string('status')->default('active')->after('phone');

            // ইনডেক্স করা হয়েছে দ্রুত কুয়েরি করার জন্য (Sovereign Optimization)
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
        });
    }
};