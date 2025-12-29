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
        // This migration is specifically for production deployment
        // It safely handles the status constraint without assuming existing types
        
        if (DB::getDriverName() === 'pgsql') {
            // Get all existing check constraints on the transactions table
            $constraints = DB::select("
                SELECT conname as constraint_name
                FROM pg_constraint 
                WHERE conrelid = (
                    SELECT oid FROM pg_class WHERE relname = 'transactions'
                ) AND contype = 'c'
                AND conname LIKE '%status%'
            ");
            
            // Drop all status-related constraints
            foreach ($constraints as $constraint) {
                DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS \"{$constraint->constraint_name}\"");
            }
            
            // Ensure column is varchar type
            DB::statement("ALTER TABLE transactions ALTER COLUMN status TYPE VARCHAR(20)");
            
            // Add the new constraint
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled', 'voided'))");
            
        } else {
            // For MySQL - use Laravel's schema builder
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('status', 20)->default('completed')->change();
            });
            
            // Add check constraint for MySQL 8.0+
            try {
                DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled', 'voided'))");
            } catch (Exception $e) {
                // MySQL versions before 8.0 don't support check constraints
                // This is fine, the application will handle validation
            }
        }
        
        // Ensure void columns exist (safe to run multiple times)
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'void_reason')) {
                $table->string('void_reason')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'voided_by')) {
                $table->unsignedBigInteger('voided_by')->nullable();
                $table->foreign('voided_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('transactions', 'voided_at')) {
                $table->timestamp('voided_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove void columns
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'voided_by')) {
                $table->dropForeign(['voided_by']);
            }
            $table->dropColumn(['void_reason', 'voided_by', 'voided_at']);
        });
        
        // Restore original constraint
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check");
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled'))");
        }
    }
};