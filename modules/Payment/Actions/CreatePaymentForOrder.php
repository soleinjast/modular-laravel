<?php

namespace Modules\Payment\Actions;

use Modules\Order\Exceptions\PaymentFailedException;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Payment\PayBuddy;
use RuntimeException;

class CreatePaymentForOrder
{
    /**
     * @param int $orderId
     * @param int $userId
     * @param int $totalInCents
     * @param PayBuddy $payBuddy
     * @param string $paymentToken
     * @return Payment
     * @throws PaymentFailedException
     */
    public function handle(int $orderId, int $userId, int $totalInCents,  PayBuddy $payBuddy, string $paymentToken): Payment
    {
        try {
            $charge = $payBuddy->charge(
                $paymentToken, $totalInCents, "modularization");
        }catch (RuntimeException $exception){
            throw PaymentFailedException::dueToInvalidToken();
        }
        return Payment::query()->create([
            'total_in_cents' => $totalInCents,
            'status' => 'paid',
            'payment_gateway' => 'PayBuddy',
            'payment_id' => $charge['id'],
            'user_id' => $userId,
            'order_id' => $orderId
        ]);
    }
}
