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
        // Check if digital payment method already exists
        $schema = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='transactions'");
        $tableSql = $schema[0]->sql ?? '';
        
        if (strpos($tableSql, "'digital'") !== false) {
            // Digital payment method already exists, nothing to do
            return;
        }
        
        // If we reach here, the digital payment method needs to be added
        // This should not happen in the current state, but keeping the logic for safety
        throw new Exception('Digital payment method not found in schema. Manual intervention required.');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to reverse since we didn't change anything
    }
};