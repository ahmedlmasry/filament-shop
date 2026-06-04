<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class CartItemResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "price"=> $this->price,
            "quantity"=> $this->quantity,
            "subtotal"=> $this->price * $this->quantity,
            "product"=> [
                'id'         => $this->product->id,
                'name'       => $this->product->name,
                'base_image' => $this->getImageUrl($this->product->firstImage?->file_name, 'public'),
            ],
             'variant'  => $this->variant ? new ProductVariantResource($this->variant) : null,
        ];
    }

}
