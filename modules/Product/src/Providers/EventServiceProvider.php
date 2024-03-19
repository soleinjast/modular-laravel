<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Checkout\OrderFulFilled;
use Modules\Product\Events\DecreaseProductStock;


class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderFulFilled::class => [
            DecreaseProductStock::class
        ]
    ];
}
