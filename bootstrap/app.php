
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Admin'=>\App\Http\Middleware\AdminMiddleware::class,
            'Employee'=>\App\Http\Middleware\EmployeeMiddleware::class,
            'Merchant'=>\App\Http\Middleware\MerchantMiddleware::class,
            'Customer'=>\App\Http\Middleware\CustomerMiddleware::class,
            'Role'=>\App\Http\Middleware\RoleMiddleware::class,
            'check.blocked' => \App\Http\Middleware\CheckBlocked::class,
            'language'=>\App\Http\Middleware\LanguageMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
