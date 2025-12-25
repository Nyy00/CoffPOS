<?php

/**
 * Script to fix payment_method enum in production
 * This adds 'digital' to the allowed payment methods
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Fixing Payment Method Enum ===\n\n";

try {
    $driver = DB::getDriverName();
    echo "Database Driver: $driver\n";
    
    if ($driver === 'pgsql') {
        echo "Attempting to fix PostgreSQL enum constraint...\n";
        
        // Method 1: Try to drop and recreate the constraint
        try {
            DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_payment_method_check");
            echo "✅ Dropped existing constraint\n";
            
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method IN ('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital'))");
            echo "✅ Added new constraint with 'digital' option\n";
            
        } catch (Exception $e) {
            echo "❌ Constraint method failed: " . $e->getMessage() . "\n";
            
            // Method 2: Try to modify the column directly
            try {
                echo "Trying alternative method...\n";
                DB::statement("ALTER TABLE transactions ALTER COLUMN payment_method TYPE VARCHAR(20)");
                echo "✅ Changed payment_method to VARCHAR\n";
                
                DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method IN ('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital'))");
                echo "✅ Added constraint to VARCHAR column\n";
                
            } catch (Exception $e2) {
                echo "❌ Alternative method failed: " . $e2->getMessage() . "\n";
                throw $e2;
            }
        }
        
    } elseif ($driver === 'mysql') {
        echo "Fixing MySQL enum...\n";
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital')");
        echo "✅ Updated MySQL enum\n";
        
    } else {
        echo "⚠️  Unsupported database driver: $driver\n";
    }
    
    // Test the fix
    echo "\nTesting the fix...\n";
    
    // Try to insert a test record (we'll roll it back)
    DB::beginTransaction();
    
    try {
        DB::table('transactions')->insert([
            'user_id' => 1,
            'transaction_code' => 'TEST-DIGITAL-' . time(),
            'subtotal' => 10000,
            'discount' => 0,
            'tax' => 1000,
            'total' => 11000,
            'payment_method' => 'digital',
            'payment_amount' => 11000,
            'change_amount' => 0,
            'status' => 'completed',
            'transaction_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Test insert successful - 'digital' payment method is now allowed\n";
        
        // Rollback the test transaction
        DB::rollback();
        echo "✅ Test transaction rolled back\n";
        
    } catch (Exception $e) {
        DB::rollback();
        echo "❌ Test insert failed: " . $e->getMessage() . "\n";
        throw $e;
    }
    
    echo "\n🎉 Payment method enum fix completed successfully!\n";
    echo "You can now use 'digital' as a payment method.\n";
    
} catch (Exception $e) {
    echo "\n💥 Error: " . $e->getMessage() . "\n";
    echo "Please check the database manually or contact support.\n";
    exit(1);
}