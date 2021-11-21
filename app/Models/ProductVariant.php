<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    protected $fillable = ['variant', 'variant_id'];

    /**
     * Variant of product variant
     *
     * @return BelongsTo
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * First variant price
     *
     * @return HasOne
     */
    public function variant_one_price(): HasOne
    {
        return $this->hasOne(ProductVariantPrice::class, 'product_variant_one');
    }

    /**
     * Second variant price
     *
     * @return HasOne
     */
    public function variant_two_price(): HasOne
    {
        return $this->hasOne(ProductVariantPrice::class, 'product_variant_two');
    }

    /**
     * Third variant price
     *
     * @return HasOne
     */
    public function variant_three_price(): HasOne
    {
        return $this->hasOne(ProductVariantPrice::class, 'product_variant_three');
    }
}
