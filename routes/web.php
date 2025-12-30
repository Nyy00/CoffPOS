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

// Test P&L API without auth (remove after debugging)
Route::get('/debug/pl-api', function () {
    try {
        $controller = app(\App\Http\Controllers\Admin\ExpenseController::class);
        $request = new \Illuminate\Http\Request();
        $request->merge(['period' => '12months', 'year' => 2025]);
        
        $response = $controller->getProfitLossData($request);
        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});

// Test database connection
Route::get('/debug/db-test', function () {
    try {
        $transactionCount = \App\Models\Transaction::count();
        $expenseCount = \App\Models\Expense::count();
        $customerCount = \App\Models\Customer::count();
        
        return response()->json([
            'database_connection' => 'OK',
            'transactions_count' => $transactionCount,
            'expenses_count' => $expenseCount,
            'customers_count' => $customerCount,
            'sample_transaction' => \App\Models\Transaction::first(),
            'sample_expense' => \App\Models\Expense::first(),
            'sample_customer' => \App\Models\Customer::first()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'database_connection' => 'FAILED',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test products search functionality
Route::get('/debug/products-search', function () {
    try {
        $searchTerm = request('search', 'pudding');
        
        // Get all products first
        $allProducts = \App\Models\Product::with('category')->get();
        
        // Test search query
        $searchResults = \App\Models\Product::with('category')
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            })
            ->get();
        
        return response()->json([
            'success' => true,
            'search_term' => $searchTerm,
            'all_products_count' => $allProducts->count(),
            'search_results_count' => $searchResults->count(),
            'all_products' => $allProducts->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'category' => $p->category->name ?? 'No Category'
                ];
            }),
            'search_results' => $searchResults->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'category' => $p->category->name ?? 'No Category'
                ];
            }),
            'database_connection' => 'OK'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Test customer API directly
Route::get('/debug/customers-api', function () {
    try {
        $customers = \App\Models\Customer::select('id', 'name', 'phone', 'email', 'points')
            ->orderBy('name')
            ->limit(10)
            ->get();
            
        return response()->json([
            'success' => true,
            'customers' => $customers,
            'count' => $customers->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

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

// Storage routes for Railway (when symbolic link fails)
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $allowedFolders = ['products', 'categories', 'avatars', 'receipts', 'images'];
    
    if (!in_array($folder, $allowedFolders)) {
        abort(404);
    }
    
    $file = storage_path("app/public/{$folder}/{$filename}");
    
    if (!file_exists($file)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($file);
    
    return response()->file($file, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('filename', '[A-Za-z0-9\-_\.]+')->name('storage.file');

// Fallback routes for images in public/images (backward compatibility)
Route::get('/images/{folder}/{filename}', function ($folder, $filename) {
    $allowedFolders = ['products', 'categories', 'avatars', 'receipts'];
    
    if (!in_array($folder, $allowedFolders)) {
        abort(404);
    }
    
    // First try storage
    $storageFile = storage_path("app/public/{$folder}/{$filename}");
    if (file_exists($storageFile)) {
        $mimeType = mime_content_type($storageFile);
        return response()->file($storageFile, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
    
    // Then try public/images
    $publicFile = public_path("images/{$folder}/{$filename}");
    if (file_exists($publicFile)) {
        $mimeType = mime_content_type($publicFile);
        return response()->file($publicFile, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
    
    abort(404);
})->where('filename', '[A-Za-z0-9\-_\.]+')->name('images.file');
