<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

// Debug routes - only for development/testing
if (app()->environment(['local', 'staging'])) {
    Route::get('/debug/midtrans', function () {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');
        
        return response()->json([
            'environment' => app()->environment(),
            'midtrans_config' => [
                'server_key_set' => !empty($serverKey),
                'server_key_prefix' => $serverKey ? substr($serverKey, 0, 15) . '...' : 'NOT SET',
                'client_key_set' => !empty($clientKey),
                'client_key_prefix' => $clientKey ? substr($clientKey, 0, 15) . '...' : 'NOT SET',
                'is_production' => $isProduction,
                'is_sanitized' => config('midtrans.is_sanitized'),
                'is_3ds' => config('midtrans.is_3ds'),
            ],
            'env_vars' => [
                'MIDTRANS_SERVER_KEY' => env('MIDTRANS_SERVER_KEY') ? 'SET' : 'NOT SET',
                'MIDTRANS_CLIENT_KEY' => env('MIDTRANS_CLIENT_KEY') ? 'SET' : 'NOT SET',
                'MIDTRANS_IS_PRODUCTION' => env('MIDTRANS_IS_PRODUCTION', 'NOT SET'),
            ]
        ]);
    });
}

// Production-safe health check
Route::get('/health/midtrans', function () {
    $serverKey = config('midtrans.server_key');
    $clientKey = config('midtrans.client_key');
    
    $response = [
        'midtrans_configured' => !empty($serverKey) && !empty($clientKey),
        'is_production' => config('midtrans.is_production'),
        'environment' => app()->environment(),
        'server_key_set' => !empty($serverKey),
        'client_key_set' => !empty($clientKey),
        'server_key_prefix' => $serverKey ? substr($serverKey, 0, 15) . '...' : 'NOT SET',
        'client_key_prefix' => $clientKey ? substr($clientKey, 0, 15) . '...' : 'NOT SET',
    ];
    
    return response()->json($response);
});

// HTML version for browser access
Route::get('/health/midtrans/html', function () {
    $serverKey = config('midtrans.server_key');
    $clientKey = config('midtrans.client_key');
    
    $html = '
    <html>
    <head><title>Midtrans Configuration Check</title></head>
    <body style="font-family: Arial, sans-serif; padding: 20px;">
        <h1>Midtrans Configuration Status</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr><td><strong>Environment</strong></td><td>' . app()->environment() . '</td></tr>
            <tr><td><strong>Server Key Set</strong></td><td>' . (!empty($serverKey) ? 'YES' : 'NO') . '</td></tr>
            <tr><td><strong>Client Key Set</strong></td><td>' . (!empty($clientKey) ? 'YES' : 'NO') . '</td></tr>
            <tr><td><strong>Server Key Prefix</strong></td><td>' . ($serverKey ? substr($serverKey, 0, 15) . '...' : 'NOT SET') . '</td></tr>
            <tr><td><strong>Client Key Prefix</strong></td><td>' . ($clientKey ? substr($clientKey, 0, 15) . '...' : 'NOT SET') . '</td></tr>
            <tr><td><strong>Is Production</strong></td><td>' . (config('midtrans.is_production') ? 'YES' : 'NO') . '</td></tr>
            <tr><td><strong>Is Sanitized</strong></td><td>' . (config('midtrans.is_sanitized') ? 'YES' : 'NO') . '</td></tr>
            <tr><td><strong>Is 3DS</strong></td><td>' . (config('midtrans.is_3ds') ? 'YES' : 'NO') . '</td></tr>
            <tr><td><strong>Configured</strong></td><td>' . ((!empty($serverKey) && !empty($clientKey)) ? 'YES' : 'NO') . '</td></tr>
        </table>
        
        <h2>Environment Variables Check</h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr><td><strong>MIDTRANS_SERVER_KEY</strong></td><td>' . (env('MIDTRANS_SERVER_KEY') ? 'SET' : 'NOT SET') . '</td></tr>
            <tr><td><strong>MIDTRANS_CLIENT_KEY</strong></td><td>' . (env('MIDTRANS_CLIENT_KEY') ? 'SET' : 'NOT SET') . '</td></tr>
            <tr><td><strong>MIDTRANS_IS_PRODUCTION</strong></td><td>' . env('MIDTRANS_IS_PRODUCTION', 'NOT SET') . '</td></tr>
        </table>
    </body>
    </html>';
    
    return response($html)->header('Content-Type', 'text/html');
});

// Test Midtrans API connection
Route::get('/health/midtrans/test', function () {
    try {
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production');
        
        if (empty($serverKey)) {
            return response()->json(['error' => 'Server key not configured'], 500);
        }
        
        // Test with a simple API call to check if credentials work
        $url = $isProduction ? 'https://api.midtrans.com/v2/ping' : 'https://api.sandbox.midtrans.com/v2/ping';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':')
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        return response()->json([
            'server_key_prefix' => substr($serverKey, 0, 15) . '...',
            'is_production' => $isProduction,
            'api_url' => $url,
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $error,
            'success' => $httpCode === 200
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

// Fix payment method enum and missing columns
Route::get('/admin/fix-payment-enum', function () {
    try {
        $output = [];
        $driver = DB::getDriverName();
        $output[] = "Database Driver: $driver";
        
        // First, run the missing columns migration
        $output[] = "Running missing columns migration...";
        try {
            Artisan::call('migrate', ['--path' => 'database/migrations/2025_12_25_151500_add_missing_payment_columns_to_transactions_table.php']);
            $output[] = "✅ Missing columns migration completed";
        } catch (Exception $e) {
            $output[] = "⚠️ Migration may have already run: " . $e->getMessage();
        }
        
        if ($driver === 'pgsql') {
            $output[] = "Attempting to fix PostgreSQL enum constraint...";
            
            try {
                DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_payment_method_check");
                $output[] = "✅ Dropped existing constraint";
                
                DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method IN ('cash', 'debit', 'credit', 'ewallet', 'qris', 'digital'))");
                $output[] = "✅ Added new constraint with 'digital' option";
                
            } catch (Exception $e) {
                $output[] = "❌ Constraint error: " . $e->getMessage();
                return response()->json(['success' => false, 'output' => $output], 500);
            }
            
        } else {
            $output[] = "⚠️ Only PostgreSQL fix is implemented";
        }
        
        $output[] = "🎉 Payment system fix completed successfully!";
        return response()->json(['success' => true, 'output' => $output]);
        
    } catch (Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
})->middleware(['auth', 'role:admin']);

// Debug logo and storage
Route::get('/debug/logo', function () {
    $storagePath = storage_path('app/public/logo.png');
    $publicPath = public_path('storage/logo.png');
    $fallbackPath = public_path('images/logo-fallback.png');
    $placeholderPath = public_path('images/placeholder-product.png');
    
    return response()->json([
        'storage_logo_exists' => file_exists($storagePath),
        'storage_logo_size' => file_exists($storagePath) ? filesize($storagePath) : 0,
        'public_logo_exists' => file_exists($publicPath),
        'public_logo_size' => file_exists($publicPath) ? filesize($publicPath) : 0,
        'fallback_logo_exists' => file_exists($fallbackPath),
        'fallback_logo_size' => file_exists($fallbackPath) ? filesize($fallbackPath) : 0,
        'placeholder_exists' => file_exists($placeholderPath),
        'placeholder_size' => file_exists($placeholderPath) ? filesize($placeholderPath) : 0,
        'storage_link_exists' => is_link(public_path('storage')),
        'storage_url' => asset('storage/logo.png'),
        'fallback_url' => asset('images/logo-fallback.png'),
        'placeholder_url' => asset('images/placeholder-product.png'),
    ]);
});