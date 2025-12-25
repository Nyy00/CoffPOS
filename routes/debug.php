<?php

use Illuminate\Support\Facades\Route;

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