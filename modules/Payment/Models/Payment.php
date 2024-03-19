<?php

namespace Modules\Payment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Models\Order;
use Modules\Payment\PaymentProvider;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
      'payment_gateway' => PaymentProvider::class
    ];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
