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
        // For PostgreSQL, we need to handle the constraint properly
        if (DB::getDriverName() === 'pgsql') {
            // First, check if we're dealing with an enum column or check constraint
            $constraintExists = DB::select("
                SELECT constraint_name 
                FROM information_schema.table_constraints 
                WHERE table_name = 'transactions' 
                AND constraint_type = 'CHECK' 
                AND constraint_name LIKE '%status%'
            ");
            
            // Drop existing constraint if it exists
            if (!empty($constraintExists)) {
                foreach ($constraintExists as $constraint) {
                    DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS {$constraint->constraint_name}");
                }
            }
            
            // Convert column to varchar if it's not already
            DB::statement("ALTER TABLE transactions ALTER COLUMN status TYPE VARCHAR(20)");
            
            // Add new constraint with voided status
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_status_check CHECK (status IN ('completed', 'cancelled', 'voided'))");
            
        } else {
            // For MySQL and other databases
            Schema::table('transactions', function (Blueprint $table) {
                $table->enum('status', ['completed', 'cancelled', 'voided'])->default('completed')->change();
            });
        }
        
        // Add void-related columns
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'void_reason')) {
                $table->string('void_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('transactions', 'voided_by')) {
                $table->foreignId('voided_by')->nullable()->constrained('users')->onDelete('set null')->after('void_reason');
            }
            if (!Schema::hasColumn('transactions', 'voided_at')) {
                $table->timestamp('voided_at')->nullable()->after('voided_by');
            }
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