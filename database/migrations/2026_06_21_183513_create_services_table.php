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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade'); // কোন ব্যবসার সার্ভিস
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_minutes'); // সার্ভিসটি দিতে কত মিনিট লাগবে (যেমন: 30, 60)
            $table->decimal('price', 8, 2); // সার্ভিসের দাম
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
