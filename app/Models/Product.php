<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    /**
     * Variant prices
     *
     * @return HasMany
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductVariantPrice::class)->with(['variant_one', 'variant_two', 'variant_three']);
    }

    /**
     * Product variants
     *
     * @return HasMany
     */
    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Product images
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
