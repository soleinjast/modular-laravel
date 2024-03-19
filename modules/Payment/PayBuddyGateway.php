<?php

namespace Modules\Payment;

use Modules\Payment\Exceptions\PaymentFailedException;
use RuntimeException;

class PayBuddyGateway implements PaymentGateway
{
    public function __construct(protected PayBuddySdk $payBuddySdk)
    {

    }

    public function charge(PaymentDetails $paymentDetails): SuccessfulPayment
    {
        try {
            $charge = $this->payBuddySdk->charge(
                $paymentDetails->token,
                $paymentDetails->amountInCents,
                $paymentDetails->statementDescription
            );
        }catch (RuntimeException $exception){
            throw new PaymentFailedException($exception->getMessage());
        }

            return new SuccessfulPayment($charge['id'], $charge['amount_in_cents'], $this->id());
    }

    public function id(): PaymentProvider
    {
        return PaymentProvider::PayBuddy;
    }
}
