<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;
use App\Models\User;

echo "=== UPDATE TRANSACTION FOR TESTING ===\n\n";

// Update the most recent transaction to be within 24 hours
$transaction = Transaction::where('status', 'completed')->latest()->first();

if ($transaction) {
    echo "Updating transaction: {$transaction->transaction_code}\n";
    echo "Old created_at: {$transaction->created_at}\n";
    
    // Update created_at to be within last hour
    $transaction->update([
        'created_at' => now()->subMinutes(30)
    ]);
    
    echo "New created_at: {$transaction->fresh()->created_at}\n";
    echo "Hours ago: " . $transaction->fresh()->created_at->diffInHours(now()) . "\n";
    echo "Can void now: " . ($transaction->fresh()->created_at->diffInHours(now()) <= 24 ? 'YES' : 'NO') . "\n";
    
    echo "\n✅ Transaction updated for testing void functionality!\n";
} else {
    echo "No transactions found.\n";
}