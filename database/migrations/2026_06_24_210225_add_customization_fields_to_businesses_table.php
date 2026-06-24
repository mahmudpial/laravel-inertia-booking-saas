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
        Schema::table('businesses', function (Blueprint $blueprint) {
            // Adds 'description' column only if it does not exist
            if (!Schema::hasColumn('businesses', 'description')) {
                $blueprint->text('description')->nullable()->after('name');
            }

            // Adds 'logo' column only if it does not exist
            if (!Schema::hasColumn('businesses', 'logo')) {
                $blueprint->string('logo')->nullable()->after('description');
            }

            // Adds 'banner' column only if it does not exist
            if (!Schema::hasColumn('businesses', 'banner')) {
                $blueprint->string('banner')->nullable()->after('logo');
            }

            // Adds 'address' column only if it does not exist
            if (!Schema::hasColumn('businesses', 'address')) {
                $blueprint->string('address')->nullable()->after('banner');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $blueprint) {
            $columnsToDrop = [];

            if (Schema::hasColumn('businesses', 'description')) {
                $columnsToDrop[] = 'description';
            }
            if (Schema::hasColumn('businesses', 'logo')) {
                $columnsToDrop[] = 'logo';
            }
            if (Schema::hasColumn('businesses', 'banner')) {
                $columnsToDrop[] = 'banner';
            }
            if (Schema::hasColumn('businesses', 'address')) {
                $columnsToDrop[] = 'address';
            }

            if (!empty($columnsToDrop)) {
                $blueprint->dropColumn($columnsToDrop);
            }
        });
    }
};