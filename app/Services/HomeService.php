<?php

namespace App\Services;

use App\DTOs\HomeData;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    public function getHomeData(): HomeData
    {
        return Cache::remember('home_data', now()->addHours(6), function () {
            return new HomeData(
                sliders: $this->getSliders(),
                brands: $this->getBrands(),
                categories: $this->getCategories(),
                newArrivals: $this->getNewArrivals(),
                saleProducts: $this->getSaleProducts(),
                flashProducts: $this->getFlashProductsWithTimer(),
            );
        });
    }
    private function getFlashProductsWithTimer(): Collection
    {
        return Cache::remember('flash_products', now()->endOfDay(), function () {
            return Product::active()
                ->withDetails()
                ->hasDiscount()
                ->whereNotNull('available_for')
                ->whereDate('available_for', today())
                ->latest()
                ->limit(10)
                ->get();
        });
    }
    private function getSliders(): Collection
    {
        return Slider::active()->limit(3)->get();
    }
    private function getCategories(): Collection
    {
        return Category::active()->limit(10)->get();
    }
    private function getBrands(): Collection
    {
        return Brand::active()->limit(10)->get();
    }
    private function getNewArrivals(): Collection
    {
        return Product::active()->withDetails()->latest()->limit(10)->get();
    }
    private function getSaleProducts(): Collection
    {
        return Product::active()->withDetails()->hasDiscount()->latest()->limit(10)->get();
    }
}
