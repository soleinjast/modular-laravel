<?php

namespace Modules\Payment\Actions;

use Illuminate\Support\Str;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Models\Payment;
use Modules\Payment\PaymentGateway;
use Modules\Payment\PaymentProvider;

class CreatePaymentForOrderInMemory implements CreatePaymentForOrderInterface
{
    protected bool $shouldFail = false;
    public array $payments = [];
    public function handle(int $orderId, int $userId, int $totalInCents, PaymentGateway $paymentGateway, string $paymentToken): Payment
    {
        if (!$this->shouldFail){
            $payment = new Payment(['order_id' => $orderId,
                'user_id' => $userId,
                'total_in_cents' => $totalInCents,
                'payment_gateway' => PaymentProvider::InMemory,
                'payment_id' => (string) Str::uuid()
            ]);
            $this->payments[] = $payment;
            return $payment;
        }else{
            throw new PaymentFailedException();
        }
    }

    public function shouldFail(): void
    {
        $this->shouldFail  = true;
    }
}
