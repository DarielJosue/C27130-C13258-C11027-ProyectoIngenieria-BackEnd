<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\URL;
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

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
        RateLimiter::for('api', function (Request $request) {
            // 60 peticiones por minuto, por usuario o IP
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}