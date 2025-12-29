<?php

use App\Http\Controllers\DebugController;
use Illuminate\Support\Facades\Route;

// Debug routes - only accessible in production for troubleshooting
Route::middleware(['web'])->group(function () {
    // Railway comprehensive debug tool
    Route::get('/railway-debug', [DebugController::class, 'railwayDebug'])->name('debug.railway');
    
    // Test upload functionality
    Route::post('/debug/test-upload', [DebugController::class, 'testUpload'])->name('debug.test-upload');
});