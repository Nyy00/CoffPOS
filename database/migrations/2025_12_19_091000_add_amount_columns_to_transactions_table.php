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
        Schema::table('transactions', function (Blueprint $table) {
            // Add missing amount columns if they don't exist
            if (!Schema::hasColumn('transactions', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->nullable()->after('transaction_code');
            }
            if (!Schema::hasColumn('transactions', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal_amount');
            }
            if (!Schema::hasColumn('transactions', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->after('discount_amount');
            }
            if (!Schema::hasColumn('transactions', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->after('tax_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumnIfExists('subtotal_amount');
            $table->dropColumnIfExists('discount_amount');
            $table->dropColumnIfExists('tax_amount');
            $table->dropColumnIfExists('total_amount');
        });
    }
};