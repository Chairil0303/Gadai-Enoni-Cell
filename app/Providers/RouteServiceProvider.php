<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Cabang;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
        parent::boot();
        Route::model('cabang', Cabang::class);

        Route::aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
        Route::middleware('role')->group(base_path('routes/web.php'));

    }

    /**
     * Custom redirect based on role
     */
    public static function home()
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'Superadmin') {
                return '/dashboard/superadmin';
            } elseif (auth()->user()->role === 'Admin') {
                return '/dashboard/admin';
            }else  {
                return '/dashboard_nasabah';
            }
        }

        return '/';
    }
}
