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
            'adminauth' => \App\Http\Middleware\AdminMiddleware::class,
            'userauth' => \App\Http\Middleware\UserMiddleware::class,
            'apikey' => App\Http\Middleware\ApiKeyMiddleware::class,
            'api' => [
                'key' => env('API_KEY', 'pefwufuifnuefnuifnufnqweufwoqfwiefjwefpqwoefwfwqfef'),
            ],
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
