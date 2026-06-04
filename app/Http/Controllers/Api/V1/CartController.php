<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\CartResource;
use App\Http\Resources\WishlistResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductVariant;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    public function getAll(Request $request)
    {
        $cart = $request->user()->cart()->firstOrCreate();
        $cart->load('items.product.firstImage', 'items.variant.variantAttributes.AttributeValue.attribute');
        return $this->apiResponse(200, __('cart.get'), new CartResource($cart));
    }
    public function addToCart(Request $request, Product $product)
    {
        $cart = $request->user()->cart()->firstOrCreate();

        $price = $request->product_variant_id ? ProductVariant::findOrFail($request->product_variant_id)->price : $product->price;

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }
        $cart->load('items.product.firstImage', 'items.variant.variantAttributes.AttributeValue.attribute');
        return $this->apiResponse(200, __('cart.added'), new CartResource($cart));
    }


    public function updateQuantity(Request $request, CartItem $item)
    {
        Gate::authorize('modify', $item);


        $item->update([
            'quantity' => $request->quantity,
        ]);
        $item->cart->load('items.product.firstImage', 'items.variant.variantAttributes.AttributeValue.attribute');

        return $this->apiResponse(200, __('cart.updated'), new CartResource($item->cart));
    }

    public function remove(CartItem $item)
    {
        Gate::authorize('modify', $item);
        $item->delete();
        return $this->apiResponse(200, __('cart.removed'));
    }

    public function clear(Request $request)
    {
        $request->user()->cart()->first()?->items()->delete();
        return $this->apiResponse(200, __('cart.cleared'));
    }

}
