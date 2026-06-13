<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    private float $subtotal;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->subtotal = $this->items->sum(fn($item) => $item->price * $item->quantity);
        return [
            'id' => $this->id,
            'coupon' => $this->coupon ? new CouponResource($this->coupon) : null,
            'items' => CartItemResource::collection($this->items),
            'summary' => [
                'subtotal' => $this->subtotal,
                'discount' => $this->calculateDiscount(),
                'total' => $this->subtotal - $this->calculateDiscount()
            ]
        ];
    }
    private function calculateDiscount()
    {
        if (!$this->coupon)
            return 0;
        return $this->subtotal * ($this->coupon->discount_precentage / 100);

    }
}
