<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Collection;

final readonly class HomeData
{
    public function __construct(
        public Collection $sliders,
        public Collection $categories,
        public Collection $brands,
        public Collection $newArrivals,
        public Collection $saleProducts,
        public Collection $flashProducts,
    ) {
    }
}