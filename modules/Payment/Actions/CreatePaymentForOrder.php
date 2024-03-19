<?php

namespace Modules\Payment\Actions;

use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Models\Payment;
use Modules\Payment\PaymentDetails;
use Modules\Payment\PaymentGateway;
use RuntimeException;

class CreatePaymentForOrder
{
    /**
     * @param int $orderId
     * @param int $userId
     * @param int $totalInCents
     * @param PaymentGateway $paymentGateway
     * @param string $paymentToken
     * @return Payment
     * @throws PaymentFailedException
     */
    public function handle(int $orderId, int $userId, int $totalInCents,  PaymentGateway $paymentGateway, string $paymentToken): Payment
    {
        $charge = $paymentGateway->charge(
            new PaymentDetails(
                $paymentToken,
                $totalInCents,
                "modularization"));

        return Payment::query()->create([
            'total_in_cents' => $totalInCents,
            'status' => 'paid',
            'payment_gateway' => $charge->paymentProvider,
            'payment_id' => $charge->id,
            'user_id' => $userId,
            'order_id' => $orderId
        ]);
    }
}
