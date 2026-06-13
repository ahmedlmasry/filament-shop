<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ProductResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            'has_variants' => $this->has_variants,
            'has_discount' => $this->has_discount,
            'sku' => $this->sku,
            'avalabilty' => $this->available_for,
            'price' => $this->has_variants ? $this->variants->min('price') : $this->price,
            'discount' => $this->has_variants ? $this->variants->max('discount') : $this->discount,
            'price_after_discount' => $this->has_variants ? null : $this->price_after_discount,
            "base_image" => $this->getImageUrl($this->firstImage?->file_name, 'public'),
            "all_images" => $this->getImagesUrl($this->images, 'public'),
            "category" => $this->category?->name,
            'brand' => $this->brand?->name,
            'is_in_wishlist' => auth()->check()
                ? ($request->user()->wishlist()->where('product_id', $this->id)->exists()) ? 1 : 0
                : 0,
            'created_at' => $this->formatDate($this->created_at, 'l, d F Y h:i A'),
        ];
    }
}
