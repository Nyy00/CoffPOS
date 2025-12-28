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
        // Fix payment method constraint to allow 'digital' payment method
        
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // For PostgreSQL, drop the old constraint and create a new one
            try {
                // Drop existing constraint
                DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_payment_method_check");
                
                // Add new constraint that includes 'digital'
                DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method IN ('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital'))");
                
                echo "Successfully updated payment_method constraint to include 'digital'\n";
            } catch (Exception $e) {
                echo "Error updating constraint: " . $e->getMessage() . "\n";
                throw $e;
            }
        } elseif ($driver === 'mysql') {
            // For MySQL, modify the enum
            try {
                DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital')");
                echo "Successfully updated payment_method enum to include 'digital'\n";
            } catch (Exception $e) {
                echo "Error updating enum: " . $e->getMessage() . "\n";
                throw $e;
            }
        } else {
            // For SQLite (development), recreate the table
            echo "SQLite detected - payment method constraint will be handled by Laravel validation\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing this change could break existing digital transactions
        // So we'll leave the constraint as is
        echo "Rollback not implemented to preserve existing digital transactions\n";
    }
};