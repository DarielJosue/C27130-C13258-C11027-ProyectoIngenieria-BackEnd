<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;

return Application::configure()
    ->withRouting(
        web: base_path('routes/web.php'),
        api: base_path('routes/api.php'),
        apiPrefix: '',
        commands: base_path('routes/console.php'),
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ability' => CheckForAnyAbility::class,
            'abilities' => CheckAbilities::class,
        ]);

        $middleware->api(prepend: [
            SubstituteBindings::class,
        ]);

        $middleware->api(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();