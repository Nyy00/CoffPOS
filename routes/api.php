<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for AJAX calls - Basic implementation
Route::middleware(['auth'])->prefix('v1')->group(function () {
    // Basic API routes will be added here as controllers are implemented
});


/*
|--------------------------------------------------------------------------
| PUBLIC API ROUTES (Bisa diakses tanpa Login)
|--------------------------------------------------------------------------
*/

Route::get('/coffee-quotes', function () {
    // 1. Daftar 20 Quotes Kopi
    $quotes = [
        "Life begins after coffee.",
        "Coffee smells like magic and fairytales.",
        "Procaffeinating: The tendency to not start anything until you've had a cup of coffee.",
        "Coffee: because adulting is hard.",
        "Rise and grind.",
        "Today's good mood is sponsored by coffee.",
        "Coffee is a hug in a mug.",
        "Espresso yourself.",
        "Behind every successful person is a substantial amount of coffee.",
        "Coffee first. Schemes later.",
        "But first, coffee.",
        "A yawn is a silent scream for coffee.",
        "Coffee is the best Monday motivation.",
        "Stay grounded, fly high.",
        "Life is too short for bad coffee.",
        "Good days start with coffee and you.",
        "Coffee: The gasoline of life.",
        "Drink coffee and do good.",
        "May your coffee be strong and your Monday be short.",
        "Happiness is a warm cup of coffee."
    ];

    // 2. Acak Quotes
    $randomQuote = $quotes[array_rand($quotes)];

    // 3. Kembalikan sebagai JSON
    return response()->json([
        'status' => 'success',
        'quote' => $randomQuote
    ]);
});