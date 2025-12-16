<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\User;
use App\Models\Expense;
use App\Models\Transaction;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('pos', function (Request $request) {
            return Limit::perMinute(200)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Route Model Bindings
        $this->configureRouteModelBinding();
    }

    /**
     * Configure route model bindings for better performance
     */
    protected function configureRouteModelBinding(): void
    {
        // Product binding with category relationship
        Route::bind('product', function (string $value) {
            return Product::with('category')->findOrFail($value);
        });

        // Category binding with products count
        Route::bind('category', function (string $value) {
            return Category::withCount('products')->findOrFail($value);
        });

        // Customer binding with transaction stats
        Route::bind('customer', function (string $value) {
            return Customer::withCount('transactions')
                ->withSum('transactions', 'total')
                ->findOrFail($value);
        });

        // User binding with role information
        Route::bind('user', function (string $value) {
            return User::withCount(['transactions', 'expenses'])->findOrFail($value);
        });

        // Expense binding with user information
        Route::bind('expense', function (string $value) {
            return Expense::with('user:id,name')->findOrFail($value);
        });

        // Transaction binding with full relationships
        Route::bind('transaction', function (string $value) {
            return Transaction::with([
                'user:id,name',
                'customer:id,name,phone',
                'transactionItems.product:id,name,price'
            ])->findOrFail($value);
        });

        // Custom route patterns
        Route::pattern('id', '[0-9]+');
        Route::pattern('product', '[0-9]+');
        Route::pattern('category', '[0-9]+');
        Route::pattern('customer', '[0-9]+');
        Route::pattern('user', '[0-9]+');
        Route::pattern('expense', '[0-9]+');
        Route::pattern('transaction', '[0-9]+');
    }
}