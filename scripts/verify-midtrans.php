<?php

/**
 * Script to verify Midtrans configuration
 * Run with: php scripts/verify-midtrans-safe.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

echo "=== Midtrans Configuration Verification ===\n\n";

// Check if Midtrans keys are set
$serverKey = env('MIDTRANS_SERVER_KEY');
$clientKey = env('MIDTRANS_CLIENT_KEY');
$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

echo "Environment: " . (app()->environment()) . "\n";
echo "Midtrans Production Mode: " . ($isProduction ? 'YES' : 'NO') . "\n\n";

// Check Server Key
if (empty($serverKey)) {
    echo "❌ MIDTRANS_SERVER_KEY is not set or empty\n";
} else {
    $keyType = $isProduction ? 'Production' : 'Sandbox';
    $expectedPrefix = $isProduction ? 'Mid-server-' : 'SB-Mid-server-';
    
    if (strpos($serverKey, $expectedPrefix) === 0) {
        echo "✅ MIDTRANS_SERVER_KEY is properly configured ($keyType)\n";
        echo "   Key prefix: " . substr($serverKey, 0, 15) . "...\n";
    } else {
        echo "⚠️  MIDTRANS_SERVER_KEY format might be incorrect for $keyType environment\n";
        echo "   Expected prefix: $expectedPrefix\n";
        echo "   Current prefix: " . substr($serverKey, 0, 15) . "...\n";
    }
}

// Check Client Key
if (empty($clientKey)) {
    echo "❌ MIDTRANS_CLIENT_KEY is not set or empty\n";
} else {
    $keyType = $isProduction ? 'Production' : 'Sandbox';
    $expectedPrefix = $isProduction ? 'Mid-client-' : 'SB-Mid-client-';
    
    if (strpos($clientKey, $expectedPrefix) === 0) {
        echo "✅ MIDTRANS_CLIENT_KEY is properly configured ($keyType)\n";
        echo "   Key prefix: " . substr($clientKey, 0, 15) . "...\n";
    } else {
        echo "⚠️  MIDTRANS_CLIENT_KEY format might be incorrect for $keyType environment\n";
        echo "   Expected prefix: $expectedPrefix\n";
        echo "   Current prefix: " . substr($clientKey, 0, 15) . "...\n";
    }
}

echo "\n=== Configuration Summary ===\n";
echo "Server Key Set: " . (!empty($serverKey) ? 'YES' : 'NO') . "\n";
echo "Client Key Set: " . (!empty($clientKey) ? 'YES' : 'NO') . "\n";
echo "Production Mode: " . ($isProduction ? 'YES' : 'NO') . "\n";
echo "Sanitized: " . (env('MIDTRANS_IS_SANITIZED', true) ? 'YES' : 'NO') . "\n";
echo "3DS Enabled: " . (env('MIDTRANS_IS_3DS', true) ? 'YES' : 'NO') . "\n";

if (empty($serverKey) || empty($clientKey)) {
    echo "\n❌ Midtrans is NOT properly configured!\n";
    echo "Please set MIDTRANS_SERVER_KEY and MIDTRANS_CLIENT_KEY in your environment.\n";
    exit(1);
} else {
    echo "\n✅ Midtrans configuration looks good!\n";
    exit(0);
}