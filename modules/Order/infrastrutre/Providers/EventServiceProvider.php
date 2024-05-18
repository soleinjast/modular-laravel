<?php

namespace Modules\Order\infrastrutre\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Checkout\OrderFulFilled;
use Modules\Order\Checkout\OrderStarted;
use Modules\Order\Checkout\SendOrderConfirmationEmail;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderStarted::class => [
            SendOrderConfirmationEmail::class
        ],
    ];
}
