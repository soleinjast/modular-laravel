<?php

namespace Modules\Order\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Order\Events\OrderFulFilled;
use Modules\Order\Events\SendOrderConfirmationEmail;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        OrderFulFilled::class => [
            SendOrderConfirmationEmail::class
        ]
    ];
}
