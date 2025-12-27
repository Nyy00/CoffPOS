<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sync data from old columns to new columns
        DB::statement('UPDATE transactions SET 
            subtotal_amount = subtotal,
            discount_amount = discount,
            tax_amount = tax,
            total_amount = total
            WHERE subtotal_amount IS NULL OR total_amount IS NULL OR total_amount = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data sync
    }
};