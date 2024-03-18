<?php

namespace Modules\Shipment\Providers;

use Carbon\Laravel\ServiceProvider;

class ShipmentServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->app->register(RouteServiceProvider::class);
    }
}
