<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'sliders'            => SliderResource::collection($this->resource->sliders),
            'brands'            => BrandResource::collection($this->resource->brands),
            'categories'        => CategoryResource::collection($this->resource->categories),
            'new_arrivals'      => ProductResource::collection($this->resource->newArrivals),
            'sale_products'     => ProductResource::collection($this->resource->saleProducts),
            'flash_products'    => ProductResource::collection($this->resource->flashProducts),
        ];
    }
}
