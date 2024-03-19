<?php

namespace Modules\Order\DTOs;

use Modules\Payment\PaymentGateway;

readonly class PendingPayment
{

    public function __construct(public PaymentGateway $paymentGateway, public string $paymentToken)
    {

    }
}
