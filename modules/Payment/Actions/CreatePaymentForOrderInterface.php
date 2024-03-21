<?php

namespace Modules\Payment\Actions;

use Modules\Payment\Models\Payment;
use Modules\Payment\PaymentGateway;

interface CreatePaymentForOrderInterface
{
    public function handle(int $orderId, int $userId, int $totalInCents,  PaymentGateway $paymentGateway, string $paymentToken): Payment;
}
