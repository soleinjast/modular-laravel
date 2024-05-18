<?php

namespace Modules\Payment\Providers;

use App\Providers\EventServiceProvider as BaseServiceProvider;
use Modules\Order\Checkout\OrderStarted;
use Modules\Order\CompleteOrder;
use Modules\Order\MarkOrderAsFailed;
use Modules\Order\NotifyUserOfPaymentFailure;
use Modules\Payment\PaymentFailed;
use Modules\Payment\PaymentSucceeded;
use Modules\Payment\PayOrder;

class EventServiceProvider extends BaseServiceProvider
{
    protected $listen = [
        OrderStarted::class => [
            PayOrder::class
        ],
        PaymentFailed::class => [
            MarkOrderAsFailed::class,
            NotifyUserOfPaymentFailure::class,
        ],
        PaymentSucceeded::class => [
            CompleteOrder::class,
        ],
    ];
}
