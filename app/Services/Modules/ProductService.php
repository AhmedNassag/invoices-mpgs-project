<?php

namespace App\Services\Modules;

use App\Models\Product;

class ProductService
{
    public function saveProduct(Product $product, $request): Product
    {
        $product->name = $request->name;
        $product->price = $request->price;
        $product->unit_id = $request->unit_id;
        $product->status = $request->status;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->save();

        return $product;
    }

    public function saveImage($product, $request)
    {
        if ($request->file('image')) {
            $product->media()->delete();
            $product->addMedia($request->file('image'))->toMediaCollection('item');
        }
    }
}