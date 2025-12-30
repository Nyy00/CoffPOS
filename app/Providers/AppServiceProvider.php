<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URLs in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // Handle proxy headers (for Railway, Heroku, etc.)
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        }

        // Register custom Blade directives for image handling
        // Only register if Blade is available and cache path is set
        try {
            if (app()->bound('view') && config('view.compiled')) {
                \Illuminate\Support\Facades\Blade::directive('productImage', function ($expression) {
                    return "<?php echo \App\Helpers\ImageHelper::getProductImageUrl($expression); ?>";
                });

                \Illuminate\Support\Facades\Blade::directive('categoryImage', function ($expression) {
                    return "<?php echo \App\Helpers\ImageHelper::getCategoryImageUrl($expression); ?>";
                });

                \Illuminate\Support\Facades\Blade::directive('avatarImage', function ($expression) {
                    return "<?php echo \App\Helpers\ImageHelper::getAvatarUrl($expression); ?>";
                });

                \Illuminate\Support\Facades\Blade::directive('receiptImage', function ($expression) {
                    return "<?php echo \App\Helpers\ImageHelper::getReceiptUrl($expression); ?>";
                });
            }
        } catch (\Exception $e) {
            // Ignore blade directive registration errors during build
            if (config('app.debug')) {
                \Log::warning('Blade directive registration failed: ' . $e->getMessage());
            }
        }
    }
}
