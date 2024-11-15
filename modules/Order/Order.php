<?php

namespace Modules\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Enumerations\OrderEnums;
use Modules\Payment\Models\Payment;
use Modules\Product\Collections\CartItemCollection;
use NumberFormatter;

class Order extends Model
{
    use HasFactory;

    const PAYMENT_FAILED = 'payment_failed';
    protected $fillable = ['user_id', 'total_in_cents', 'status'];

    public const PENDING = "pending";
    public const COMPLETED = "completed";


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lines() : HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function url() : string
    {
        return route('order::order.show', $this);
    }

    public static function startForUser(int $userId)
    {
        return self::make([
            'user_id' => $userId,
            'status' => OrderEnums::PENDING
        ]);
    }

    public function localizedTotal(): string
    {
        return (new NumberFormatter('en-US', NumberFormatter::CURRENCY))->formatCurrency($this->total_in_cents / 100, 'USD');
    }

    public function addLinesFromCartItems(CartItemCollection $items): void
    {
        foreach ($items->items() as $item){
            $this->lines->push(OrderLine::query()->make([
                'product_id' => $item->product->productId,
                'product_price_in_cents' => $item->product->priceInCents,
                'quantity' => $item->quantity
            ]));
        }

        $this->total_in_cents = $this->lines->sum(fn(OrderLine $orderLine) => $orderLine->product_price_in_cents * $orderLine->quantity);
    }

    /**
     * @throws OrderMissingOrderLinesException
     */
    public function start(): void
    {
        if($this->lines->isEmpty()){
            throw new OrderMissingOrderLinesException();
        }
        $this->status = OrderEnums::PENDING;
        $this->save();
        $this->lines()->saveMany($this->lines);
    }

    public function markedAsFail(): void
    {
        $this->status = self::PAYMENT_FAILED;
        $this->save();
    }

    public function complete(): void
    {
        $this->status = self::COMPLETED;
        $this->save();
    }
}
