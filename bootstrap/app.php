<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register custom middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'active' => \App\Http\Middleware\CheckActive::class,
        ]);

        // DO NOT include HandleInertiaRequests - we're using Blade, not Inertia
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withProviders([
        \SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
        \Barryvdh\DomPDF\ServiceProvider::class,
        \Barryvdh\Snappy\ServiceProvider::class,
    ])->create();
    