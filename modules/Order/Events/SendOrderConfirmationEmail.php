<?php

namespace Modules\Order\Events;

use Illuminate\Support\Facades\Mail;
use Modules\Order\OrderReceived;

class SendOrderConfirmationEmail
{
    public function handle(OrderFulFilled $event)
    {
        Mail::to($event->user->email)->send(new OrderReceived($event->order->localizedTotal));
    }
}
