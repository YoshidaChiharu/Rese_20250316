<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthAdminMiddleware;
use App\Http\Middleware\AuthAdministratorMiddleware;
use App\Http\Middleware\AuthShopOwnerMiddleware;
use App\Http\Middleware\MyShopsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AuthAdminMiddleware::class,
            'administrator' => AuthAdministratorMiddleware::class,
            'shop_owner' => AuthShopOwnerMiddleware::class,
            'my_shops' => MyShopsMiddleware::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
