@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="{{ request()->input('title') }}">
                </div>
                <div class="col-md-2">
                    <x-variant-select />
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control" value="{{ request()->input('price_from') }}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control" value="{{ request()->input('price_to') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{ request()->input('date') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($products as $product)
                        <tr>
                            <td>{{ $loop->index + $products->firstItem() }}</td>
                            <td>{{ $product->title }} <br> Created at : {{ $product->created_at->diffForHumans() }}</td>
                            <td class="px-3">{{ $product->description }}</td>
                            <td width="400">
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant_{{ $product->id }}">
                                    @foreach($product->prices as $variantPrice)
                                        <dt class="col-sm-4 pb-0 px-0">
                                            {{ optional($variantPrice->variant_one)->variant . '/' }} {{ optional($variantPrice->variant_two)->variant . '/' }} {{ optional($variantPrice->variant_three)->variant }}
                                        </dt>
                                        <dd class="col-sm-8">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-6 pb-0">Price : {{ number_format($variantPrice->price, 2) }}</dt>
                                                <dd class="col-sm-6 pb-0">InStock : {{ number_format($variantPrice->stock, 2) }}</dd>
                                            </dl>
                                        </dd>
                                    @endforeach
                                </dl>
                                <button onclick="$('#variant_{{ $product->id }}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    {{ pageDetails($products) }}
                </div>
                <div class="col-md-4 d-flex justify-content-end">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
