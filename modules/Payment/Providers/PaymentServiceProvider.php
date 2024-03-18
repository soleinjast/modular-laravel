<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->app->register(RouteServiceProvider::class);
    }
}
