<?php

namespace Modules\Order;

use Modules\Payment\PaymentFailed;

class MarkOrderAsFailed
{
    public function handle(PaymentFailed $event): void
    {
        Order::query()->find($event->order->id)->markedAsFail();
    }
}
