<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;

echo "=== ALL TRANSACTIONS ===\n\n";

$transactions = Transaction::orderBy('created_at', 'desc')->take(10)->get();

foreach ($transactions as $transaction) {
    echo "ID: {$transaction->id}\n";
    echo "Code: {$transaction->transaction_code}\n";
    echo "Status: {$transaction->status}\n";
    echo "Created: {$transaction->created_at}\n";
    echo "Hours ago: " . $transaction->created_at->diffInHours(now()) . "\n";
    echo "Can void: " . ($transaction->status === 'completed' && $transaction->created_at->diffInHours(now()) <= 24 ? 'YES' : 'NO') . "\n";
    echo "---\n";
}