<?php

namespace App\Repositories;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Collection;

class VariantRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Variant);
    }

    /**
     * Get all variants by product id
     *
     * @param int $productId product Identifier
     * @return Collection
     */
    public function getAllWithProductVariantByProductId(int $productId): Collection
    {
        return $this->model->with(['productVariants' => function($query) use ($productId) {
            $query->where('product_id', $productId);
        }])->get();
    }

    public function getAllVariantsWithDistinctProductVariants(): Collection
    {
        return $this->model->with(['productVariants' => function($q) {
            $q->select('variant', 'variant_id')->distinct();
        }])->get();
    }
}
