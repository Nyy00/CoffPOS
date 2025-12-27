<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReportController;
// use App\Http\Controllers\Admin\SettingsController; // Commented out - controller doesn't exist
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are all the admin routes for the CoffPOS application.
| These routes are protected by auth and role middleware.
|
*/

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::controller(DashboardController::class)->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('stats', 'getStats')->name('stats');
    Route::get('charts/{type}', 'getChartData')->name('charts');
    Route::get('recent-activities', 'getRecentActivities')->name('recent-activities');
    Route::get('top-customers', 'getTopCustomers')->name('top-customers');
    Route::get('low-stock-alerts', 'getLowStockAlerts')->name('low-stock-alerts');
    Route::get('performance-metrics', 'getPerformanceMetrics')->name('performance-metrics');
});

// Products Management
Route::resource('products', ProductController::class);
Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
    Route::get('search/api', 'search')->name('search.api');
    Route::get('filter', 'filter')->name('filter.api');
    Route::post('{product}/update-stock', 'updateStock')->name('update-stock');
    Route::get('export/csv', 'export')->name('export');
    Route::post('bulk-delete', 'bulkDelete')->name('bulk-delete');
    Route::post('bulk-update-stock', 'bulkUpdateStock')->name('bulk-update-stock');
    Route::post('bulk-toggle-availability', 'bulkToggleAvailability')->name('bulk-toggle-availability');
    Route::get('low-stock/alert', 'getLowStockAlert')->name('low-stock.alert');
    Route::get('{product}/history', 'getHistory')->name('history');
});

// Categories Management
Route::resource('categories', CategoryController::class);
Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
    Route::get('api/list', 'getCategories')->name('api.list');
    Route::delete('{category}/remove-image', 'removeImage')->name('remove-image');
    Route::get('export/csv', 'export')->name('export');
    Route::post('bulk-delete', 'bulkDelete')->name('bulk-delete');
    Route::get('{category}/products-count', 'getProductsCount')->name('products-count');
});

// Customers Management
Route::resource('customers', CustomerController::class);
Route::controller(CustomerController::class)->prefix('customers')->name('customers.')->group(function () {
    Route::get('search/api', 'search')->name('search.api');
    Route::get('filter', 'filter')->name('filter.api');
    Route::get('{customer}/transactions', 'getTransactionHistory')->name('transactions');
    Route::post('{customer}/update-points', 'updatePoints')->name('update-points');
    Route::get('export/csv', 'export')->name('export');
    Route::post('bulk-delete', 'bulkDelete')->name('bulk-delete');
    Route::post('bulk-update-points', 'bulkUpdatePoints')->name('bulk-update-points');
    Route::get('{customer}/loyalty-stats', 'getLoyaltyStats')->name('loyalty-stats');
    Route::post('{customer}/send-notification', 'sendNotification')->name('send-notification');
});

// Expenses Management
Route::resource('expenses', ExpenseController::class);
Route::controller(ExpenseController::class)->prefix('expenses')->name('expenses.')->group(function () {
    Route::get('search/api', 'search')->name('search.api');
    Route::get('filter', 'filter')->name('filter.api');
    Route::delete('{expense}/remove-receipt', 'removeReceipt')->name('remove-receipt');
    Route::get('stats/api', 'getStats')->name('stats.api');
    Route::get('dashboard/api', 'getDashboardData')->name('dashboard.api');
    Route::get('export/csv', 'export')->name('export');
    Route::get('chart-data/api', 'getChartData')->name('chart-data.api');
    Route::get('profit-loss/api', 'getProfitLossData')->name('profit-loss.api');
    Route::post('bulk-delete', 'bulkDelete')->name('bulk-delete');
    Route::get('categories/stats', 'getCategoryStats')->name('categories.stats');
    Route::get('monthly/comparison', 'getMonthlyComparison')->name('monthly.comparison');
});

// Transactions Management (Admin View)
Route::controller(TransactionController::class)->prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('{transaction}', 'show')->name('show');
    Route::post('{transaction}/void', 'void')->name('void');
    Route::get('export/csv', 'export')->name('export');
    Route::get('search/api', 'search')->name('search.api');
    Route::get('filter', 'filter')->name('filter.api');
    Route::get('stats/api', 'getStats')->name('stats.api');
    Route::post('{transaction}/reprint-receipt', 'reprintReceipt')->name('reprint-receipt');
    Route::get('daily/summary', 'getDailySummary')->name('daily.summary');
    Route::get('payment-methods/stats', 'getPaymentMethodStats')->name('payment-methods.stats');
});

// Users Management (Admin Only)
Route::middleware('role:admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::post('{user}/reset-password', 'resetPassword')->name('reset-password');
        Route::post('{user}/change-role', 'changeRole')->name('change-role');
        Route::delete('{user}/remove-avatar', 'removeAvatar')->name('remove-avatar');
        Route::get('{user}/stats', 'getStats')->name('stats');
        Route::get('export/csv', 'export')->name('export');
        Route::post('bulk-delete', 'bulkDelete')->name('bulk-delete');
        Route::post('bulk-change-role', 'bulkChangeRole')->name('bulk-change-role');
        Route::get('{user}/activity-log', 'getActivityLog')->name('activity-log');
        Route::post('{user}/toggle-status', 'toggleStatus')->name('toggle-status');
    });
});

// Reports Routes
Route::controller(ReportController::class)->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('daily', 'daily')->name('daily');
    Route::get('monthly', 'monthly')->name('monthly');
    Route::get('products', 'products')->name('products');
    Route::get('stock', 'stock')->name('stock');
    Route::get('profit-loss', 'profitLoss')->name('profit-loss');
    Route::get('customers', 'customers')->name('customers');
    Route::get('expenses', 'expenses')->name('expenses');
    Route::get('cashier-performance', 'cashierPerformance')->name('cashier-performance');
    Route::get('custom', 'customReport')->name('custom');
    Route::get('data/{type}', 'getReportData')->name('data');
});

// Settings Routes (Admin Only) - Commented out until SettingsController is created
/*
Route::middleware('role:admin')->controller(SettingsController::class)->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('update', 'update')->name('update');
    Route::post('backup-database', 'backupDatabase')->name('backup-database');
    Route::post('clear-cache', 'clearCache')->name('clear-cache');
    Route::get('system-info', 'systemInfo')->name('system-info');
    Route::post('optimize-images', 'optimizeImages')->name('optimize-images');
    Route::get('logs', 'getLogs')->name('logs');
    Route::post('maintenance-mode', 'toggleMaintenanceMode')->name('maintenance-mode');
});
*/

// Bulk Operations Routes
Route::prefix('bulk')->name('bulk.')->group(function () {
    Route::post('products/update', [ProductController::class, 'bulkUpdate'])->name('products.update');
    Route::post('categories/update', [CategoryController::class, 'bulkUpdate'])->name('categories.update');
    Route::post('customers/update', [CustomerController::class, 'bulkUpdate'])->name('customers.update');
    Route::post('expenses/update', [ExpenseController::class, 'bulkUpdate'])->name('expenses.update');
    Route::post('transactions/void', [TransactionController::class, 'bulkVoid'])->name('transactions.void');
});

// Quick Actions Routes
Route::prefix('quick')->name('quick.')->group(function () {
    Route::post('product/add', [ProductController::class, 'quickAdd'])->name('product.add');
    Route::post('category/add', [CategoryController::class, 'quickAdd'])->name('category.add');
    Route::post('customer/add', [CustomerController::class, 'quickAdd'])->name('customer.add');
    Route::post('expense/add', [ExpenseController::class, 'quickAdd'])->name('expense.add');
});

// Import/Export Routes - Commented out until ImportExportController is created
/*
Route::prefix('import-export')->name('import-export.')->group(function () {
    Route::get('/', [ImportExportController::class, 'index'])->name('index');
    Route::post('products/import', [ImportExportController::class, 'importProducts'])->name('products.import');
    Route::post('customers/import', [ImportExportController::class, 'importCustomers'])->name('customers.import');
    Route::get('template/{type}', [ImportExportController::class, 'downloadTemplate'])->name('template');
    Route::get('export-all', [ImportExportController::class, 'exportAll'])->name('export-all');
});
*/