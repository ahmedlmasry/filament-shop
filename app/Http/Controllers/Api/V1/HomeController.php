<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\HomeResource;
use App\Services\HomeService;
use Illuminate\Http\JsonResponse;

class HomeController extends BaseController
{
    public function __construct(private HomeService $homeService){}
    public function getHomeData(): JsonResponse
    {
        return $this->apiResponse(200, 'Data Retrieved Successfully', new HomeResource($this->homeService->getHomeData()));
    }

}
