<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class WishlistResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "wishlist_id" => $this->id,
            "user_id" => $this->user_id,
            "product_name" => $this->product->name,
            "product_id" => $this->product->id,
            'product_price' => $this->product->has_variants
                ? $this->product->variants->min('price')
                : $this->product->price,
            "product_image" => $this->getImageUrl($this->product->firstImage?->file_name, 'public'),
        ];
    }
}
