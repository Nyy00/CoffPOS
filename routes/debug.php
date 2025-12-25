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
    
    return response()->json([
        'midtrans_configured' => !empty($serverKey) && !empty($clientKey),
        'is_production' => config('midtrans.is_production'),
        'environment' => app()->environment(),
    ]);
});