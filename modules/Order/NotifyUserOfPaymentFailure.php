<?php

namespace Modules\Order;

use Illuminate\Support\Facades\Mail;
use Modules\Payment\PaymentFailed;
use Modules\Payment\PaymentForOrderFailed;

class NotifyUserOfPaymentFailure
{
    public function handle(PaymentFailed $event): void
    {
        Mail::to($event->user->email)->send(new PaymentForOrderFailed($event->order, $event->reason));
    }
}
