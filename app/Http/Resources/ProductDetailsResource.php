<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'small_desc' => $this->small_desc,
            'desc' => $this->desc,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'manage_stock' => $this->manage_stock,
            'has_discount' => $this->has_discount,
            'has_variants' => $this->has_variants,
            'price' => $this->price,
            'discount' => $this->discount,
            'price_after_discount' => $this->price_after_discount,
            "all_images" => $this->getImagesUrl($this->images, 'public'),
            'category' => $this->category?->name,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'reviews' => ProductPreviewResource::collection($this->productPreviews),
        ];
    }
}
