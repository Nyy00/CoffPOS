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
        // For PostgreSQL, we need to alter the enum type
        if (DB::getDriverName() === 'pgsql') {
            // Add the new enum value to the existing type
            DB::statement("ALTER TYPE transactions_status_check ADD VALUE IF NOT EXISTS 'voided'");
            
            // If the above doesn't work, we'll drop and recreate the constraint
            try {
                DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check");
                DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled', 'voided'))");
            } catch (Exception $e) {
                // Fallback: modify the column directly
                DB::statement("ALTER TABLE transactions ALTER COLUMN status TYPE VARCHAR(20)");
                DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled', 'voided'))");
            }
        } else {
            // For MySQL and other databases
            Schema::table('transactions', function (Blueprint $table) {
                $table->enum('status', ['completed', 'cancelled', 'voided'])->default('completed')->change();
            });
        }
        
        // Add void-related columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('void_reason')->nullable()->after('status');
            $table->foreignId('voided_by')->nullable()->constrained('users')->onDelete('set null')->after('void_reason');
            $table->timestamp('voided_at')->nullable()->after('voided_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove void-related columns
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['voided_by']);
            $table->dropColumn(['void_reason', 'voided_by', 'voided_at']);
        });
        
        // Revert status enum
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_status_check");
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled'))");
        } else {
            Schema::table('transactions', function (Blueprint $table) {
                $table->enum('status', ['completed', 'cancelled'])->default('completed')->change();
            });
        }
    }
};