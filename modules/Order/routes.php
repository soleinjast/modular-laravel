<?php


use Modules\Order\Http\Controllers\CheckoutController;
use Modules\Order\Models\Order;

\Illuminate\Support\Facades\Route::middleware(['auth'])->group(function (){
    \Illuminate\Support\Facades\Route::post('checkout',
        CheckoutController::class)->name('checkout');

    \Illuminate\Support\Facades\Route::get('/orders/{order}', function (Order $order){
       return $order;
    })->name('order.show');
});
