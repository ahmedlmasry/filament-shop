<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\PasswordService;
use Illuminate\Http\JsonResponse;

class PasswordController extends BaseController
{
    public function __construct(private PasswordService $passwordService) {}
    public function forgetPassword(ForgetPasswordRequest $request) :JsonResponse
    {
        $identfier = $request->filled('email') ? $request->email : $request->mobile;
        $this->passwordService->forgetPassword($identfier);
        return $this->apiResponse(200, 'otp send');
    }
    public function resetPassword(ResetPasswordRequest $request) :JsonResponse
    {
        $this->passwordService->resetPassword($request->email, $request->otp, $request->password);
        return $this->apiResponse(200, 'password updated');
    }
}
