<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;

class AuthController extends BaseController
{
    public function __construct(private AuthService $authService){}
    public function register(StoreUserRequest $request)
    {
        $result = $this->authService->register($request->validated());
        return $this->apiResponse(201, __('auth.registered_successfully'), new AuthResource($result));
    }
    public function login(LoginUserRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);
        return $this->apiResponse(200, __('auth.login'), new AuthResource($result));
    }
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return $this->apiResponse(200, __('auth.logout'));
    }
}
