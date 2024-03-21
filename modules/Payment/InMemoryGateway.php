<?php

namespace Modules\Payment;

use Illuminate\Support\Str;

class InMemoryGateway implements PaymentGateway
{
    public function charge(PaymentDetails $paymentDetails): SuccessfulPayment
    {
        return new SuccessfulPayment(
            (string) Str::uuid(),
            $paymentDetails->amountInCents,
            $this->id()
        );
    }

    public function id(): PaymentProvider
    {
        return  PaymentProvider::InMemory;
    }
}
