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
        $middleware->alias([
        'customer' => \App\Http\Middleware\CustomerMiddleware::class,
        'merchant' => \App\Http\Middleware\MerchantMiddleware::class,
        'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'check.blocked' => \App\Http\Middleware\CheckBlocked::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
