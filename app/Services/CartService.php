<?php

namespace App\Services;

use App\Exceptions\CouponNotValidException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\ProductVariant;


class CartService
{
    public function getAll($request): Cart
    {
        return $this->getCartRelations($this->getUserCart($request));
    }
    public function applyCoupon($request): Cart
    {
        $coupon = Coupon::valid()->where('code', $request->code)->first();
        if (!$coupon) {
            throw new CouponNotValidException();
        }
        $cart = $this->getUserCart($request);
        $cart->update(['coupon_id' => $coupon->id]);
        return $this->getCartRelations($cart);
    }
    public function removeCoupon($request): void
    {
        $cart = $this->getUserCart($request);
        $cart->update(['coupon_id' => null]);
    }
    public function addToCart($request, $product): Cart
    {
        $cart = $this->getUserCart($request);

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
                'quantity' => $request->quantity ?? 1,
                'price' => $price,
            ]);
        }
        return $this->getCartRelations($cart);
    }
    public function updateQuantity($request, $item): Cart
    {
        gate()->authorize('modify', $item);
        $item->update([
            'quantity' => $request->quantity,
        ]);
        return $this->getCartRelations($item->cart);
    }
    public function remove($item): void
    {
        gate()->authorize('modify', $item);
        $item->delete();
    }
    public function clear($request): void
    {
        $request->user()->cart()->first()?->items()->delete();
    }
    private function getUserCart($request): Cart
    {
        return $request->user()->cart()->firstOrCreate();
    }
    private function getCartRelations($cart): Cart
    {
        return $cart->load('items.product.firstImage', 'items.variant.variantAttributes.AttributeValue.attribute');
    }
}