<?php

namespace App\Services;

use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;

class WishlistService
{
    public function getAll($request): Collection
    {
        return Wishlist::where('user_id', $request->user()->id)
            ->with([
                'product:id,name,price,has_variants',
                'product.firstImage',
                'product.variants:id,product_id,price',
            ])->get();
    }
    public function toggle($request, $product): array
    {
        $wishlist = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return [
                'message' => __('wishlist.removed'),
                'data' => null
            ];
        }
        $wishlist = Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);
        $product->load('firstImage', 'variants');
        $wishlist->setRelation('product', $product);
        return [
            'message' => __('wislist.added'),
            'data' => new WishlistResource($wishlist)
        ];
    }
    public function removeAll($request): int
    {
        return $request->user()->wishlist()->delete();
    }
}