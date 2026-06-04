<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'coupon' => $this->coupon ? new CouponResource($this->coupon) : null,
            'items' => CartItemResource::collection($this->items),
            'summary' => [
                'subtotal' => $this->items->sum(fn($item) => $item->price * $item->quantity),
                'discount' => $this->discount,
                'total' => $this->items->sum(fn($item) => $item->price * $item->quantity) - $this->discount,
            ]
        ];
    }
}
