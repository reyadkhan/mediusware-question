<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Variant;
use App\Repositories\ProductRepository;
use App\Repositories\VariantRepository;

class ProductController extends Controller
{
    private $repository;

    private const PAGE_SIZE = 5;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        if( ! empty($filters = request()->all())) {
            request()->validate($this->filterDataRules());
            $products = $this->repository->paginateWithFilter($filters, self::PAGE_SIZE);
        } else {
            $products = $this->repository->paginate(self::PAGE_SIZE);
        }
        return view('products.index', compact('products'));
    }

    private function filterDataRules(): array
    {
        return [
            'title' => 'nullable|string',
            'date' => 'nullable|date|date_format:Y-m-d',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'variant' => 'nullable|string'
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $product = $this->repository->create($request->validated());

        if($request->expectsJson()) {
            return response()->json(compact('product'));
        }
        return redirect()->route('product.index');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(VariantRepository $variantRepo, Product $product)
    {
        $variants = $variantRepo->getAllWithProductVariantByProductId($product->id);
        $product->load(['prices', 'images']);
        return view('products.edit', compact('variants', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $updatedProduct = $this->repository->update($product, $request->validated());

        if($request->expectsJson()) {
            return response()->json($updatedProduct);
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
