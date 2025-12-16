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
        return view('admin.dashboard');
    } elseif ($user->isCashier()) {
        return redirect()->route('pos.index');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// POS Route (for cashier)
Route::middleware(['auth', 'role:cashier,admin'])->group(function () {
    Route::get('/pos', function () {
        return view('cashier.pos');
    })->name('pos.index');
});

// Admin Routes
Route::middleware(['auth', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
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

require __DIR__.'/auth.php';
