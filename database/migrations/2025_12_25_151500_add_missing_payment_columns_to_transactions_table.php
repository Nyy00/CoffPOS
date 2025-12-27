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
            // Add payment_status column if it doesn't exist
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])
                      ->default('pending')
                      ->after('status');
            }
            
            // Add subtotal_amount, discount_amount, tax_amount, total_amount if they don't exist
            if (!Schema::hasColumn('transactions', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->nullable()->after('transaction_date');
            }
            
            if (!Schema::hasColumn('transactions', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal_amount');
            }
            
            if (!Schema::hasColumn('transactions', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->nullable()->after('discount_amount');
            }
            
            if (!Schema::hasColumn('transactions', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->after('tax_amount');
            }
            
            // Add Midtrans fields if they don't exist
            if (!Schema::hasColumn('transactions', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('total_amount');
            }
            
            if (!Schema::hasColumn('transactions', 'midtrans_payment_type')) {
                $table->string('midtrans_payment_type')->nullable()->after('midtrans_transaction_id');
            }
            
            if (!Schema::hasColumn('transactions', 'midtrans_snap_token')) {
                $table->string('midtrans_snap_token')->nullable()->after('midtrans_payment_type');
            }
            
            if (!Schema::hasColumn('transactions', 'midtrans_response')) {
                $table->json('midtrans_response')->nullable()->after('midtrans_snap_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $columns = [
                'payment_status',
                'subtotal_amount',
                'discount_amount', 
                'tax_amount',
                'total_amount',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_snap_token',
                'midtrans_response'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};