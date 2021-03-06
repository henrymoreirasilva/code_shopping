<?php

namespace CodeShopping\Http\Controllers\Api;


use CodeShopping\Http\Requests\ProductOutputRequest;
use CodeShopping\Http\Resources\ProductInputResource;
use CodeShopping\Http\Resources\ProductOutputResource;
use CodeShopping\Models\Product;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Models\ProductOutput;


class ProductOutputController extends Controller
{

    public function index(Product $product)
    {
        $outputs = ProductOutput::with('product')->paginate();
        return ProductOutputResource::collection($outputs);
    }

    public function store(ProductOutputRequest $request)
    {
        $output = ProductOutput::create($request->all());
        return new ProductInputResource($output);
    }

    public function show(ProductOutput $output) // o nome da variável deve ser o nome do recurso no singular
    {
        return new ProductInputResource($output);
    }

}
