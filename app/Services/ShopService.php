<?php

namespace App\Services;


use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ShopService
{
    public function getProduct($request): LengthAwarePaginator
    {
        return Product::with('variants', 'category', 'brand', 'images', 'firstImage')
            ->active()
            ->when($request->filled('category_id'), fn($q) => $q->whereIn('category_id', $request->category_id))
            ->when($request->filled('brand_id'), fn($q) => $q->whereIn('brand_id', $request->brand_id))
            ->when(
                $request->filled('min_price') || $request->filled('max_price'),
                fn($q) => $q->priceRange($request->min_price, $request->max_price)
            )
            ->paginate(20);
    }
}