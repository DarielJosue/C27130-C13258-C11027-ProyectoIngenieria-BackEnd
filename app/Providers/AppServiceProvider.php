<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configuración de rate limit para API
        RateLimiter::for('api', function (Request $request) {
            // 60 peticiones por minuto, por usuario o IP
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}