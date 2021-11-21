<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantPrice extends Model
{
    protected $fillable = [
        'price', 'stock', 'product_variant_one', 'product_variant_two', 'product_variant_three'
    ];

    /**
     * Product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * First variant
     *
     * @return BelongsTo
     */
    public function variant_one(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_one');
    }

    /**
     * Second variant
     *
     * @return BelongsTo
     */
    public function variant_two(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_two');
    }

    /**
     * Third variant
     *
     * @return BelongsTo
     */
    public function variant_three(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_three');
    }
}
