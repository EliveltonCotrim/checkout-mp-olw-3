<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
         "name",
         "unit"
    ];

    public function skus(): BelongsToMany
    {
        return $this->belongsToMany(related: Sku::class)->using(FeatureSku::class);
    }
}
