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
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $backendNamespace = 'App\Http\Controllers\Backend';
    protected $defaultNamespace = 'App\Http\Controllers';
    protected $frontendNamespace = 'App\Http\Controllers\Frontend';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME       = '/';
    public const DASHBOARD  = '/admin';
    public const ADMIN_LOGIN = '/admin/login';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('admin')
                ->middleware('admin')
                ->namespace($this->backendNamespace)
                ->group(base_path('routes/backend.php'));

            Route::middleware('web')
                ->namespace($this->frontendNamespace)
                ->group(base_path('routes/frontend.php'));

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->defaultNamespace)
                ->group(base_path('routes/api.php'));
        });

        parent::boot();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
