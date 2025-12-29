<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;
use App\Models\User;

echo "=== TEST VOID TRANSACTION ===\n\n";

// Find a completed transaction
$transaction = Transaction::where('status', 'completed')
    ->where('created_at', '>=', now()->subHours(24))
    ->first();

if (!$transaction) {
    echo "No eligible transactions found for voiding.\n";
    echo "Creating a test transaction...\n";
    
    // You can create a test transaction here if needed
    echo "Please create a transaction through the POS first.\n";
    exit;
}

echo "Found transaction: {$transaction->transaction_code}\n";
echo "Status: {$transaction->status}\n";
echo "Created: {$transaction->created_at}\n";
echo "Hours ago: " . $transaction->created_at->diffInHours(now()) . "\n";

// Test the void update
try {
    $admin = User::where('role', 'admin')->first();
    
    $transaction->update([
        'status' => 'voided',
        'void_reason' => 'Test void from script',
        'voided_by' => $admin->id,
        'voided_at' => now()
    ]);
    
    echo "\n✅ SUCCESS: Transaction voided successfully!\n";
    echo "New status: {$transaction->fresh()->status}\n";
    echo "Void reason: {$transaction->fresh()->void_reason}\n";
    echo "Voided by: {$transaction->fresh()->voidedBy->name}\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}