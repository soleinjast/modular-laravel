<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Events\OrderFulFilled;
use Modules\Product\Events\DecreaseProductStock;


class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderFulFilled::class => [
            DecreaseProductStock::class
        ]
    ];
}
