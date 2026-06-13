<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Resources\ProductResource;
use App\Services\ShopService;
use Illuminate\Http\JsonResponse;

class ShopController extends BaseController
{
    public function __construct(private ShopService $shopService) {}

    public function getProducts(ProductFilterRequest $request): JsonResponse
    {
        $products = $this->shopService->getProduct($request);
        return $this->apiResponse(200, __('messages.products_retrieved'), ProductResource::collection($products));
    }
}
