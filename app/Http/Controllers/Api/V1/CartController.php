<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    public function __construct(private CartService $cartService)
    {
    }
    public function getAll(Request $request): JsonResponse
    {
        $cart = $this->cartService->getAll($request);
        return $this->apiResponse(200, __('cart.get'), new CartResource($cart));
    }
    public function applyCoupon(CouponRequest $request): JsonResponse
    {
        $cart = $this->cartService->applyCoupon($request);
        return $this->apiResponse(200, __('cart.coupon_applied'), new CartResource($cart));
    }
    public function removeCoupon(Request $request): JsonResponse
    {
        $this->cartService->removeCoupon($request);
        return $this->apiResponse(200, __('cart.coupon_removed'));
    }
    public function addToCart(Request $request, Product $product): JsonResponse
    {
        $cart = $this->cartService->addToCart($request, $product);
        return $this->apiResponse(200, __('cart.added'), new CartResource($cart));
    }
    public function updateQuantity(Request $request, CartItem $item): JsonResponse
    {
        $cart = $this->cartService->updateQuantity($request, $item);
        return $this->apiResponse(200, __('cart.updated'), new CartResource($cart));
    }
    public function remove(CartItem $item): JsonResponse
    {
        $this->cartService->remove($item);
        return $this->apiResponse(200, __('cart.removed'));
    }
    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clear($request);
        return $this->apiResponse(200, __('cart.cleared'));
    }

}
