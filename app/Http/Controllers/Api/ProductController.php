<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\ProductRequest;
use CodeShopping\Http\Resources\ProductResource;
use CodeShopping\Models\Product;
use CodeShopping\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query();
        $query = $this->onlyTrashedIfRequest($request, $query);
        $products = $query->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->refresh();
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->all());
        $product->update();
        return response()->json([], 204);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([], 204);
    }

    private function onlyTrashedIfRequest(Request $request, Builder $query) {
        if ($request->get('trashed') == 1) {
            $query = $query->onlyTrashed();
        }
        return $query;
    }
}
