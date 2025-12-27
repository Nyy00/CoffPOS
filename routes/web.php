<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MenuController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Dashboard - redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin() || $user->isManager()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isCashier()) {
        return redirect()->route('cashier.pos.index');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// POS routes are defined in routes/cashier.php

// Admin Routes
Route::middleware(['auth', 'role:admin,manager', 'manager.access'])->prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin.php';
});

// Cashier Routes
Route::middleware(['auth', 'role:cashier,admin'])->prefix('cashier')->name('cashier.')->group(function () {
    require __DIR__.'/cashier.php';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Debug routes
require __DIR__.'/debug.php';

// Google OAuth debug route (remove after testing)
Route::get('/debug/google-oauth', function () {
    return response()->json([
        'google_client_id' => config('services.google.client_id') ? 'Set (' . substr(config('services.google.client_id'), 0, 10) . '...)' : 'Not Set',
        'google_client_secret' => config('services.google.client_secret') ? 'Set' : 'Not Set',
        'google_redirect_url' => config('services.google.redirect'),
        'app_url' => config('app.url'),
        'app_env' => config('app.env'),
        'socialite_installed' => class_exists('Laravel\Socialite\Facades\Socialite'),
        'session_driver' => config('session.driver'),
        'session_lifetime' => config('session.lifetime'),
        'auth_user' => auth()->check() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null,
        'database_connection' => config('database.default'),
        'session_table_exists' => \Schema::hasTable('sessions'),
    ]);
});

// Debug session route
Route::get('/debug/session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'auth_check' => auth()->check(),
        'auth_user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null,
        'csrf_token' => csrf_token(),
    ]);
});

// Test manual login (remove after testing)
Route::get('/debug/test-login', function () {
    $user = \App\Models\User::first();
    if ($user) {
        \Auth::login($user);
        return response()->json([
            'message' => 'Manual login successful',
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'auth_check' => auth()->check(),
            'session_id' => session()->getId(),
        ]);
    }
    return response()->json(['message' => 'No user found']);
});

require __DIR__.'/auth.php';

// Simple storage route for specific files (fallback when symlink fails)
Route::get('/storage/logo.png', function () {
    $file = storage_path('app/public/logo.png');
    if (!file_exists($file)) {
        abort(404);
    }
    return response()->file($file, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->name('storage.logo');

Route::get('/storage/products/{filename}', function ($filename) {
    $file = storage_path('app/public/products/' . $filename);
    if (!file_exists($file)) {
        abort(404);
    }
    $mimeType = mime_content_type($file);
    return response()->file($file, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('filename', '[A-Za-z0-9\-_\.]+')->name('storage.products');
