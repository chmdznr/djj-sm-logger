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
        // Aliases preserved from old app/Http/Kernel.php::$middlewareAliases
        $middleware->alias([
            'auth'             => \App\Http\Middleware\Authenticate::class,
            'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'     => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'              => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'           => \App\Http\Middleware\ValidateSignature::class,
            'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);

        // Append custom global middleware that used to live in $middlewareGroups['web'].
        // In Laravel 11+ the default 'web' group is added automatically; we use appendToGroup
        // so we keep the same execution order as the old Kernel.
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        // The api group also needs AuthGates so JSON API requests get the same
        // per-request Gate definitions (Permission -> Role -> User).
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\AuthGates::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Preserve the default dont-flash list from old Handler.php
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);
    })->create();
