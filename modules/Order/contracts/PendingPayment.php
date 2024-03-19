<?php

namespace Modules\Order\contracts;

use Modules\Payment\PaymentGateway;

readonly class PendingPayment
{

    public function __construct(public PaymentGateway $paymentGateway, public string $paymentToken)
    {

    }
}
