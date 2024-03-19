<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\PurchaseItems;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Http\Requests\CheckoutRequest;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\PaymentGateway;
use Modules\Product\CartItemCollection;
use Modules\User\UserDto;
use Throwable;

class CheckoutController
{
    public function __construct(protected PurchaseItems $purchaseItems, protected PaymentGateway $paymentGateway)
    {

    }

    /**
     * @throws ValidationException|Throwable
     */
    public function __invoke(CheckoutRequest $request): \Illuminate\Http\JsonResponse
    {
        //providing data
        $cartItems = CartItemCollection::fromCheckOutData($request);
        $pendingPayment = new PendingPayment($this->paymentGateway, $request->input('payment_token'));
        $userDto = UserDto::fromEloquentModel($request->user());
        // send it to inner layer for business rules
        try {
            $order = $this->purchaseItems
                ->handle(
                    $cartItems,
                    $pendingPayment,
                    $userDto
                );
        }catch (PaymentFailedException){
            throw ValidationException::withMessages([
                'payment_token' => 'We could not complete your payment!'
            ]);
        }
        return response()->json([
            'order_url' => $order->url()
        ], 201);
    }
}
