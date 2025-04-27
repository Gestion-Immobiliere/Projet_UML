<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Cette constante peut servir de point d'entrée par défaut après login.
     */
    public const HOME = '/home';

    /**
     * Configurez vos routes ici.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Toutes les routes déclarées dans routes/api.php
            // seront préfixées /api et utiliseront le middleware 'api'
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Toutes les routes déclarées dans routes/web.php
            // utiliseront le middleware 'web'
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configurez le throttling (rate limiting) des API.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            // 60 requêtes/minute par user id ou par IP
            return Limit::perMinute(60)->by(
                optional($request->user())->id ?: $request->ip()
            );
        });
    }
}
