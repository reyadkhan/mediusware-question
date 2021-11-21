<?php

namespace App\Repositories;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Product);
    }

    /**
     * Paginate products
     *
     * @param int|null $perPage page size
     * @param string $orderColumn order by column
     * @param string $orderDir order direction
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = null, string $orderColumn = 'created_at', string $orderDir = 'desc'): LengthAwarePaginator
    {
        return $this->model->with('prices')->orderBy($orderColumn, $orderDir)->paginate($perPage);
    }

    /**
     * Paginate result with filter info
     *
     * @param array $filters filter data
     * @param int|null $perPage
     * @return LengthAwarePaginator
     */
    public function paginateWithFilter(array $filters, int $perPage = null): LengthAwarePaginator
    {
        $query = $this->model;
        $order = 'created_at';

        if( ! empty($filters['title'])) {
            $query = $query->where('title', 'like', $filters['title'] . '%');
            $order = 'title';
        }
        if( ! empty($filters['variant'])) {
            $query = $query->whereHas('productVariants', function ($q) use ($filters) {
                $q->where('variant', $filters['variant']);
            });
        }
        if( ! empty($filters['price_from']) && ! empty($filters['price_to'])) {
            $query = $query->whereHas('prices', function ($q) use ($filters) {
                return $q->whereBetween('price', [$filters['price_from'], $filters['price_to']]);
            });
        } elseif(! empty($filters['price_from'])) {
            $query = $query->whereHas('prices', function ($q) use ($filters) {
                return $q->where('price', '>=', $filters['price_from']);
            });
        } elseif( ! empty($filters['price_to'])) {
            $query = $query->whereHas('prices', function ($q) use ($filters) {
                return $q->where('price', '<=', $filters['price_to']);
            });
        }
        if( ! empty($filters['date'])) {
            $date = Carbon::parse($filters['date']);
            $query = $query->whereDate('created_at', '>=', $date->startOfDay())->whereDate('created_at', '<=', $date->endOfDay());
        }
        return $query->orderBy($order, 'desc')->paginate($perPage);
    }

    public function update(Product $product, array $data): Product
    {
        $product = DB::transaction(function () use ($product, $data) {
            $product->title = $data['title'];
            $product->sku = $data['sku'];
            $product->description = $data['description'];
            $product->productVariants()->delete();
            $this->createProductVariants($data['product_variant'], $product);
            $product->images()->delete();

            if ( ! empty($data['product_image'])) {
                $this->createProductImages($data['product_image'], $product);
            }
            $product->save();
            return $product->refresh();
        });
        $product->prices()->delete();
        $this->generateProductVariantPrice($data['product_variant_prices'], $product);
        return $product->refresh();
    }

    private function createProductVariants(array $variants, Product $product)
    {
        foreach ($variants as $variant) {
            $newVariants = [];

            foreach ($variant['tags'] as $tag) {
                $newVariants[] = [
                    'variant_id' => $variant['option'],
                    'variant' => $tag
                ];
            }
            $product->productVariants()->createMany($newVariants);
        }
    }

    private function createProductImages(array $productImages, Product $product)
    {
        $images = [];
        foreach($productImages as $image) {
            $images[] = $image;
        }
        $product->images()->createMany($images);
    }

    private function generateProductVariantPrice(array $variantsPrices, Product $product)
    {
        $newPrices = [];

        foreach ($variantsPrices as $price) {
            $variantTitles = explode('/', trim($price['title'], '/'));
            $variants = $product->productVariants()->whereIn('variant', $variantTitles)->get()->toArray();
            $newPrices[] = [
                'stock' => $price['stock'],
                'price' => $price['price'],
                'product_variant_one' => $variants[0]['id'] ?? null,
                'product_variant_two' => $variants[1]['id'] ?? null,
                'product_variant_three' => $variants[2]['id'] ?? null,
            ];
            $product->prices()->delete();
            $product->prices()->createMany($newPrices);
        }
    }

    /**
     * Create new product
     *
     * @param array $data product validated data
     * @return Product
     */
    public function create(array $data): Product
    {
        $product = $this->model->create($data);
        $this->createProductVariants($data['product_variant'], $product);
        $this->createProductImages($data['product_image'], $product);
        $this->generateProductVariantPrice($data['product_variant_prices'], $product);
        return $product->refresh();
    }
}
