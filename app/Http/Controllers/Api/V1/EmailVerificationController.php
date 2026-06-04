<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Auth\EmailVerificationService;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\verifyEmailRequest;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\JsonResponse;
class EmailVerificationController extends BaseController
{
    public function __construct(private EmailVerificationService $emailVerificationService)
    {
    }
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $this->emailVerificationService->sendOtp($request->user());
        return $this->apiResponse(200, 'Verification code resent.');
    }
    public function verifyEmail(verifyEmailRequest $request): JsonResponse
    {
        $this->emailVerificationService->verifyEmail($request->user(), $request->otp);
        return $this->apiResponse(200, __('auth.email_verified'));
    }
}
