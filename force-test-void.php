<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;
use App\Models\User;

echo "=== FORCE TEST VOID (IGNORE TIME CONSTRAINT) ===\n\n";

// Get any completed transaction
$transaction = Transaction::where('status', 'completed')->first();

if (!$transaction) {
    echo "No completed transactions found.\n";
    exit;
}

echo "Testing transaction: {$transaction->transaction_code}\n";
echo "Current status: {$transaction->status}\n";

try {
    $admin = User::where('role', 'admin')->first();
    
    // Test direct update to see if constraint works
    $transaction->update([
        'status' => 'voided',
        'void_reason' => 'Force test void - ignore time constraint',
        'voided_by' => $admin->id,
        'voided_at' => now()
    ]);
    
    echo "\n✅ SUCCESS: Transaction voided successfully!\n";
    echo "New status: {$transaction->fresh()->status}\n";
    echo "Void reason: {$transaction->fresh()->void_reason}\n";
    echo "Voided by: {$transaction->fresh()->voidedBy->name}\n";
    echo "Voided at: {$transaction->fresh()->voided_at}\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}