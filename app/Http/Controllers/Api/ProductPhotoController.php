<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Requests\ProductPhotoRequest;
use CodeShopping\Http\Resources\ProductPhotoCollection;
use CodeShopping\Http\Resources\ProductPhotoResource;
use CodeShopping\Models\Product;
use CodeShopping\Models\ProductPhotos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return Response
     */
    public function index(Product $product)
    {
        return new ProductPhotoCollection($product->photos, $product);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function store(ProductPhotoRequest $request, Product $product)
    {
        $photos = ProductPhotos::createWithPhotosFiles($product->id, $request->photos);
        return response()->json(new ProductPhotoCollection($photos, $product), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @param ProductPhotos $photo
     * @return Response
     */
    public function show(Product $product, ProductPhotos $photo)
    {
        $photoProduct = $product->photos()->find($photo->id);
        return $photoProduct ? \response()->json(new ProductPhotoResource($photoProduct, false), 200): \response([], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @param ProductPhotos $photo
     * @return Response
     */
    public function update(Request $request, Product $product, ProductPhotos $photo)
    {
        if ($product->id == $photo->product_id) {
            $photo->updateFile($request->newPhoto);

            return \response()->json(new ProductPhotoResource($photo), 200);
        } else {
            return response()->json([], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductPhotos $productPhotos
     * @return Response
     */
    public function destroy(Product $product, ProductPhotos $photo)
    {
        if ($product->id == $photo->product_id) {
            $photo->delete();
            return response()->json([], 204);
        } else {
            return response()->json([], 400);
        }
    }
}
