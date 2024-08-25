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




            //ADMIN
            Route::middleware('api')
                ->prefix('api/v1/admin/case')
                ->group(base_path('routes/Admin/case.php'));

            Route::middleware('api')
                ->prefix('api/v1/admin/acl')
                ->group(base_path('routes/Admin/acl.php'));

            Route::middleware('api')
                ->prefix('api/v1/admin/finance')
                ->group(base_path('routes/Admin/coupon.php'));

            Route::middleware('api')
                ->prefix('api/v1/admin')
                ->group(base_path('routes/Admin/currency.php'));

            Route::middleware('api')
                ->prefix('api/v1/admin/finance')
                ->group(base_path('routes/Admin/finance.php'));
            Route::middleware('api')
                ->prefix('api/v1/admin')
                ->group(base_path('routes/Admin/user.php'));
            Route::middleware('api')
                ->prefix('api/v1/admin')
                ->group(base_path('routes/Admin/country.php'));
            Route::middleware('api')
                ->prefix('api/v1/admin')
                ->group(base_path('routes/Admin/notification.php'));
            Route::middleware('api')
                ->prefix('api/v1/admin')
                ->group(base_path('routes/Admin/file.php'));
     

            Route::middleware('api')
                ->prefix('api/v1/expert')
                ->group(base_path('routes/Expert/case.php'));
                Route::middleware('api')
                ->prefix('api/v1/expert')
                ->group(base_path('routes/Expert/finance.php'));




            Route::middleware('api')
                ->prefix('api/v1/panel')
                ->group(base_path('routes/finance.php'));
            Route::middleware('api')
                ->prefix('api/v1/panel')
                ->group(base_path('routes/country.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/pay.php'));

            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/user.php'));
                Route::middleware('api')
                ->prefix('api/v1/panel')
                ->group(base_path('routes/notification.php'));
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/auth.php'));
            Route::middleware('api')
                ->prefix('api/v1/panel')
                ->group(base_path('routes/currency.php'));
            Route::middleware('api')
                ->prefix('api/v1/panel/case')
                ->group(base_path('routes/case.php'));
            Route::middleware('api')
            ->prefix('api/v1/panel')
                ->group(base_path('routes/country.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}








 
