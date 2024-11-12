<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderSku extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "order_id",
        "sku_id",
        "product",
        "quantity",
        "unitary_price"
    ];

    protected $casts = [
        "product" => "json",
    ];

    public function order(): BelongsTo
    {
        return $this->belonsTo(Order::class);
    }

    public function sku(): BelongsTo
    {
        return $this->belonsTo(Sku::class);
    }

}
