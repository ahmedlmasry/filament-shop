<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;

class GeneralController extends Controller
{
    public function getCities()
    {
        $cities = City::paginate(4);
        return apiResponse(200, 'success', CityResource::collection($cities));
    }
    public function getCategories()
    {
        $categories = Category::paginate(4);
        return apiResponse(200, 'success', CategoryResource::collection($categories));
    }
    public function getBrands()
    {
        $brands = Brand::paginate(4);
        return apiResponse(200, 'success', BrandResource::collection($brands));
    }

}
