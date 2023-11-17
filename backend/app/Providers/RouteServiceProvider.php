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
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            $this->mapFrontRoutes();
            $this->mapAdminRoutes();
            $this->mapSuperAdminRoutes();
            $this->mapModuleRoutes();
        });
    }

    /**
     * Define the "front" routes for the application..
     *
     * @return void
     */
    protected function mapFrontRoutes()
    {
        Route::prefix('api/front')
            ->namespace($this->namespace)
            ->group(base_path('routes/apiFront.php'));
    }

    /**
     * Define the "admin" routes for the application..
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('api/admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/apiAdmin.php'));
    }

    /**
     * Define the "super admin" routes for the application..
     *
     * @return void
     */
    protected function mapSuperAdminRoutes()
    {
        Route::prefix('api/super-admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/apiSuperAdmin.php'));
    }

    protected function mapModuleRoutes()
    {
        foreach (glob(base_path('app/Modules/*/Routes/web.php')) as $file) {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group($file);
        }

        foreach (glob(base_path('app/Modules/*/Routes/api.php')) as $file) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group($file);
        }
    }
}
