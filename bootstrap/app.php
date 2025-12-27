<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'manager.access' => \App\Http\Middleware\ManagerAccessMiddleware::class,
            'api.rate.limit' => \App\Http\Middleware\ApiRateLimitMiddleware::class,
        ]);
        
        // Apply rate limiting to API routes
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\ApiRateLimitMiddleware::class . ':120,1', // 120 requests per minute
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();