<?php

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
