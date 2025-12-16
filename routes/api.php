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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('v1')->group(function () {
    
    // Search APIs
    Route::prefix('search')->group(function () {
        Route::get('products', [ProductController::class, 'apiSearch'])->name('api.products.search');
        Route::get('customers', [CustomerController::class, 'apiSearch'])->name('api.customers.search');
        Route::get('transactions', [TransactionController::class, 'apiSearch'])->name('api.transactions.search');
        Route::get('global', [SearchController::class, 'globalSearch'])->name('api.search.global');
    });
    
    // Dashboard APIs
    Route::prefix('dashboard')->group(function () {
        Route::get('stats', [DashboardController::class, 'apiStats'])->name('api.dashboard.stats');
        Route::get('charts/{type}', [DashboardController::class, 'apiCharts'])->name('api.dashboard.charts');
        Route::get('recent-activities', [DashboardController::class, 'apiRecentActivities'])->name('api.dashboard.recent-activities');
        Route::get('notifications', [DashboardController::class, 'apiNotifications'])->name('api.dashboard.notifications');
    });
    
    // Products APIs
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'apiIndex'])->name('api.products.index');
        Route::get('{product}', [ProductController::class, 'apiShow'])->name('api.products.show');
        Route::post('{product}/update-stock', [ProductController::class, 'apiUpdateStock'])->name('api.products.update-stock');
        Route::get('low-stock/alert', [ProductController::class, 'apiLowStockAlert'])->name('api.products.low-stock');
        Route::get('{product}/sales-history', [ProductController::class, 'apiSalesHistory'])->name('api.products.sales-history');
    });
    
    // Categories APIs
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'apiIndex'])->name('api.categories.index');
        Route::get('list', [CategoryController::class, 'apiList'])->name('api.categories.list');
        Route::get('{category}/products', [CategoryController::class, 'apiProducts'])->name('api.categories.products');
    });
    
    // Customers APIs
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'apiIndex'])->name('api.customers.index');
        Route::get('{customer}', [CustomerController::class, 'apiShow'])->name('api.customers.show');
        Route::post('{customer}/update-points', [CustomerController::class, 'apiUpdatePoints'])->name('api.customers.update-points');
        Route::get('{customer}/transactions', [CustomerController::class, 'apiTransactions'])->name('api.customers.transactions');
        Route::get('{customer}/loyalty-stats', [CustomerController::class, 'apiLoyaltyStats'])->name('api.customers.loyalty-stats');
    });
    
    // Transactions APIs
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'apiIndex'])->name('api.transactions.index');
        Route::get('{transaction}', [TransactionController::class, 'apiShow'])->name('api.transactions.show');
        Route::get('stats/summary', [TransactionController::class, 'apiStatsSummary'])->name('api.transactions.stats.summary');
        Route::get('stats/daily', [TransactionController::class, 'apiStatsDaily'])->name('api.transactions.stats.daily');
        Route::get('stats/monthly', [TransactionController::class, 'apiStatsMonthly'])->name('api.transactions.stats.monthly');
    });
    
    // Expenses APIs
    Route::prefix('expenses')->group(function () {
        Route::get('/', [ExpenseController::class, 'apiIndex'])->name('api.expenses.index');
        Route::get('stats', [ExpenseController::class, 'apiStats'])->name('api.expenses.stats');
        Route::get('chart-data/{period}', [ExpenseController::class, 'apiChartData'])->name('api.expenses.chart-data');
        Route::get('categories/stats', [ExpenseController::class, 'apiCategoryStats'])->name('api.expenses.categories.stats');
    });
    
    // Reports APIs
    Route::prefix('reports')->group(function () {
        Route::get('sales/daily', [ReportController::class, 'apiDailySales'])->name('api.reports.sales.daily');
        Route::get('sales/monthly', [ReportController::class, 'apiMonthlySales'])->name('api.reports.sales.monthly');
        Route::get('products/top-selling', [ReportController::class, 'apiTopSellingProducts'])->name('api.reports.products.top-selling');
        Route::get('customers/top-spending', [ReportController::class, 'apiTopSpendingCustomers'])->name('api.reports.customers.top-spending');
    });
    
    // Notifications APIs
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'apiIndex'])->name('api.notifications.index');
        Route::post('{id}/mark-read', [NotificationController::class, 'apiMarkAsRead'])->name('api.notifications.mark-read');
        Route::post('mark-all-read', [NotificationController::class, 'apiMarkAllAsRead'])->name('api.notifications.mark-all-read');
        Route::get('unread-count', [NotificationController::class, 'apiUnreadCount'])->name('api.notifications.unread-count');
    });
    
    // System APIs
    Route::prefix('system')->group(function () {
        Route::get('health', [SystemController::class, 'healthCheck'])->name('api.system.health');
        Route::get('info', [SystemController::class, 'systemInfo'])->name('api.system.info');
        Route::get('cache/status', [SystemController::class, 'cacheStatus'])->name('api.system.cache.status');
    });
});

// Public APIs (no authentication required)
Route::prefix('public')->group(function () {
    Route::get('menu', [PublicController::class, 'getMenu'])->name('api.public.menu');
    Route::get('categories', [PublicController::class, 'getCategories'])->name('api.public.categories');
    Route::get('store-info', [PublicController::class, 'getStoreInfo'])->name('api.public.store-info');
});

// Webhook APIs
Route::prefix('webhooks')->group(function () {
    Route::post('payment/midtrans', [WebhookController::class, 'midtransCallback'])->name('api.webhooks.midtrans');
    Route::post('payment/xendit', [WebhookController::class, 'xenditCallback'])->name('api.webhooks.xendit');
});