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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'paroisse' => \App\Http\Middleware\ParoisseMiddleware::class,
            'user.status' => \App\Http\Middleware\CheckUserStatus::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'paiement/cinetpay/*',
            'paiement/cinetpay/success',
            'paiement/cinetpay/notify',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})->create();
