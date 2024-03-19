<?php


use Modules\Order\Checkout\CheckoutController;
use Modules\Order\Order;

\Illuminate\Support\Facades\Route::middleware(['auth'])->group(function (){
    \Illuminate\Support\Facades\Route::post('checkout',
        CheckoutController::class)->name('checkout');

    \Illuminate\Support\Facades\Route::get('/orders/{order}', function (Order $order){
       return $order;
    })->name('order.show');
});
