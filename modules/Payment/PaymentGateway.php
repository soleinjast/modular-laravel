<?php

namespace Modules\Payment;

interface PaymentGateway
{
    public function charge(PaymentDetails $paymentDetails): SuccessfulPayment;
    public function id() : PaymentProvider;
}
