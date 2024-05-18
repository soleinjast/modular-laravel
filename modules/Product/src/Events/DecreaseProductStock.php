<?php

namespace Modules\Product\Events;

use Modules\Order\Checkout\OrderFulFilled;
use Modules\Payment\PaymentSucceeded;
use Modules\Product\Warehouse\ProductStockManager;

class DecreaseProductStock
{
    public function __construct(protected ProductStockManager $productStockManager)
    {

    }

    public function handle(PaymentSucceeded $event): void
    {
        foreach ($event->order->lines as $cartItem){
            try {
                $this->productStockManager->decrement($cartItem->productId, $cartItem->quantity);
            }catch (\Exception $exception){
                report($exception);
            }
        }
    }
}
