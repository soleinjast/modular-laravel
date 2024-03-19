<?php

namespace Modules\Order\infrastrutre\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot() : void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->as('order::')
                ->group(__DIR__ . '/../../Ui/routes.php');
        });
    }
}
