<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureSku extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "sku_id",
        "feature_id",
        "value"
    ];

    public function sku(): BelongsTo
    {
        return $this->belonsTo(Sku::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belonsTo(Feature::class);
    }
}
