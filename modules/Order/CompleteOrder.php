<?php

namespace Modules\Order;

use Modules\Payment\PaymentSucceeded;

class CompleteOrder
{
    public function handle(PaymentSucceeded $event): void
    {
        Order::query()->find($event->order->id)->complete();
    }
}
