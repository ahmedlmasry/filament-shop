<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\WishlistResource;
use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends BaseController
{
    public function __construct(private WishlistService $wishlistService) {}
    public function getAll(Request $request): JsonResponse
    {
        $wishlist = $this->wishlistService->getAll($request);
        return $this->apiResponse(200, __('wishlist.fetched'), WishlistResource::collection($wishlist));
    }
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $result = $this->wishlistService->toggle($request, $product);
        return $this->apiResponse(200, $result['message'], $result['data']);
    }

    public function removeAll(Request $request): JsonResponse
    {
        $this->wishlistService->removeAll($request);
        return $this->apiResponse(200, __('wishlist.cleared'));
    }
}
