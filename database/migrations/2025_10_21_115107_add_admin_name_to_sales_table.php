<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add cashier/admin name
            $table->string('admin_name')->nullable()->after('price');

            // Ensure date/time column is proper timestamp
            if (!Schema::hasColumn('sales', 'date')) {
                $table->timestamp('date')->nullable()->after('admin_name');
            } else {
                // Modify existing 'date' column if needed
                $table->timestamp('date')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('admin_name');
            // Optionally revert date column if you modified it
            // $table->dateTime('date')->change();
        });
    }
};
