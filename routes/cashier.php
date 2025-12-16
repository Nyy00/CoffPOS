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
    Route::get('products/search', 'searchProducts')->name('products.search');
    Route::get('products/barcode/{barcode}', 'getProductByBarcode')->name('products.barcode');
    Route::post('cart/add', 'addToCart')->name('cart.add');
    Route::post('cart/update', 'updateCart')->name('cart.update');
    Route::delete('cart/remove', 'removeFromCart')->name('cart.remove');
    Route::delete('cart/clear', 'clearCart')->name('cart.clear');
    Route::get('cart/items', 'getCartItems')->name('cart.items');
    Route::get('cart/total', 'getCartTotal')->name('cart.total');
    Route::post('transaction/process', 'processTransaction')->name('transaction.process');
    Route::post('transaction/hold', 'holdTransaction')->name('transaction.hold');
    Route::get('transaction/held', 'getHeldTransactions')->name('transaction.held');
    Route::post('transaction/resume/{id}', 'resumeTransaction')->name('transaction.resume');
    Route::get('receipt/{transaction}', 'printReceipt')->name('receipt.print');
    Route::get('receipt/{transaction}/preview', 'previewReceipt')->name('receipt.preview');
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

// Shift Management
Route::prefix('shift')->name('shift.')->group(function () {
    Route::post('start', [ShiftController::class, 'startShift'])->name('start');
    Route::post('end', [ShiftController::class, 'endShift'])->name('end');
    Route::get('current', [ShiftController::class, 'getCurrentShift'])->name('current');
    Route::get('history', [ShiftController::class, 'getShiftHistory'])->name('history');
});

// Cash Drawer Management
Route::prefix('cash-drawer')->name('cash-drawer.')->group(function () {
    Route::post('open', [CashDrawerController::class, 'openDrawer'])->name('open');
    Route::post('close', [CashDrawerController::class, 'closeDrawer'])->name('close');
    Route::get('status', [CashDrawerController::class, 'getStatus'])->name('status');
    Route::post('count', [CashDrawerController::class, 'recordCashCount'])->name('count');
});

// Quick Reports (Cashier Level)
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('daily-sales', [CashierReportController::class, 'dailySales'])->name('daily-sales');
    Route::get('shift-summary', [CashierReportController::class, 'shiftSummary'])->name('shift-summary');
    Route::get('payment-methods', [CashierReportController::class, 'paymentMethods'])->name('payment-methods');
    Route::get('top-products', [CashierReportController::class, 'topProducts'])->name('top-products');
});

// Notifications (Cashier Specific)
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'getCashierNotifications'])->name('index');
    Route::post('{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::get('low-stock', [NotificationController::class, 'getLowStockNotifications'])->name('low-stock');
});