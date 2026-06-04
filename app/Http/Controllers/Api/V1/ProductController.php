<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProductDetailsResource;
use App\Models\Product;

class ProductController extends BaseController
{
    public function show(Product $product)
    {
        $product->load('variants.variantAttributes.AttributeValue.attribute','productPreviews.user');
        return $this->apiResponse(200, __('Product fetched successfully'), new ProductDetailsResource($product));
    }
}
