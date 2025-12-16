<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ImageService;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageService::class, function ($app) {
            return new ImageService();
        });
        
        // Register alias
        $this->app->alias(ImageService::class, 'image.service');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}