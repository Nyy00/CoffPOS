<?php

use App\Http\Controllers\Cashier\POSController;
use App\Http\Controllers\Cashier\TransactionController as CashierTransactionController;
use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cashier Routes
|--------------------------------------------------------------------------
|
| Here are all the cashier routes for the CoffPOS application.
| These routes are protected by auth and role middleware for cashiers.
|
*/

// POS System Routes
Route::controller(POSController::class)->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', 'index')->name('index');
    
    // Product search and management
    Route::get('products/search', 'searchProducts')->name('products.search');
    
    // Cart management
    Route::post('cart/add', 'addToCart')->name('cart.add');
    Route::post('cart/update', 'updateCart')->name('cart.update');
    Route::post('cart/remove', 'removeFromCart')->name('cart.remove');
    Route::post('cart/clear', 'clearCart')->name('cart.clear');
    Route::get('cart/items', 'getCartItems')->name('cart.items');
    Route::get('cart/total', 'getCartTotal')->name('cart.total');
    
    // Transaction processing
    Route::post('process-transaction', 'processTransaction')->name('transaction.process');
    
    // Test route
    Route::get('test-auth', function() {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user(),
            'role' => auth()->user()->role ?? null
        ]);
    })->name('test.auth');
    Route::post('calculate-totals', 'calculateTotals')->name('calculate.totals');
    
    // Hold transactions
    Route::post('hold-transaction', 'holdTransaction')->name('transaction.hold');
    Route::get('held-transactions', 'getHeldTransactions')->name('transaction.held');
    Route::post('resume-transaction/{holdId}', 'resumeTransaction')->name('transaction.resume');
    Route::delete('held-transaction/{holdId}', 'deleteHeldTransaction')->name('transaction.held.delete');
    
    // Receipt management
    Route::get('receipt/{transaction}', 'printReceipt')->name('receipt.print');
    Route::get('receipt/{transaction}/preview', 'previewReceipt')->name('receipt.preview');
    Route::get('receipt-data/{transactionId}', 'getReceiptData')->name('receipt.data');
    
    // Customer management for POS
    Route::get('customers/search', 'searchCustomers')->name('customers.search');
    Route::post('customers/quick-add', 'quickAddCustomer')->name('customers.quick-add');
    Route::get('customer/{customerId}/loyalty', 'getCustomerLoyalty')->name('customer.loyalty');
    
    // Daily summary
    Route::get('daily-summary', 'getDailySummary')->name('daily.summary');
});

// Cashier Transaction Management
Route::controller(CashierTransactionController::class)->prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{transaction}', 'show')->name('show');
    Route::post('{transaction}/reprint', 'reprintReceipt')->name('reprint');
    Route::get('today/summary', 'todaySummary')->name('today.summary');
    Route::get('shift/summary', 'shiftSummary')->name('shift.summary');
    Route::get('recent', 'getRecentTransactions')->name('recent');
    Route::get('stats/hourly', 'getHourlyStats')->name('stats.hourly');
});

// Customer Quick Actions (for POS)
Route::controller(CustomerController::class)->prefix('customers')->name('customers.')->group(function () {
    Route::get('quick-search', 'quickSearch')->name('quick-search');
    Route::post('quick-add', 'quickAdd')->name('quick-add');
    Route::get('{customer}/quick-info', 'getQuickInfo')->name('quick-info');
    Route::post('{customer}/apply-points', 'applyLoyaltyPoints')->name('apply-points');
});

// Shift Management - Commented out until ShiftController is created
/*
Route::prefix('shift')->name('shift.')->group(function () {
    Route::post('start', [ShiftController::class, 'startShift'])->name('start');
    Route::post('end', [ShiftController::class, 'endShift'])->name('end');
    Route::get('current', [ShiftController::class, 'getCurrentShift'])->name('current');
    Route::get('history', [ShiftController::class, 'getShiftHistory'])->name('history');
});

// Cash Drawer Management - Commented out until CashDrawerController is created
Route::prefix('cash-drawer')->name('cash-drawer.')->group(function () {
    Route::post('open', [CashDrawerController::class, 'openDrawer'])->name('open');
    Route::post('close', [CashDrawerController::class, 'closeDrawer'])->name('close');
    Route::get('status', [CashDrawerController::class, 'getStatus'])->name('status');
    Route::post('count', [CashDrawerController::class, 'recordCashCount'])->name('count');
});

// Quick Reports (Cashier Level) - Commented out until CashierReportController is created
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('daily-sales', [CashierReportController::class, 'dailySales'])->name('daily-sales');
    Route::get('shift-summary', [CashierReportController::class, 'shiftSummary'])->name('shift-summary');
    Route::get('payment-methods', [CashierReportController::class, 'paymentMethods'])->name('payment-methods');
    Route::get('top-products', [CashierReportController::class, 'topProducts'])->name('top-products');
});

// Notifications (Cashier Specific) - Commented out until NotificationController is created
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'getCashierNotifications'])->name('index');
    Route::post('{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::get('low-stock', [NotificationController::class, 'getLowStockNotifications'])->name('low-stock');
});
*/