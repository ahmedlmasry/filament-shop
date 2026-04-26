<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Ichtrojan\Otp\Otp;

class PasswordController extends Controller
{
    protected $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = User::where(function ($q) use ($request) {
            $q->where('email', $request->email)
                ->orWhere('mobile', $request->mobile);
        })->first();

        $user->notify(new ForgetPasswordNotification());
        return apiResponse(200, 'otp send');
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $otpValidation = $this->otp->validate($request->email, $request->otp);
        if (!$otpValidation->status) {
            return apiResponse(400, 'Invalid or expired OTP');
        }
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => $request->password]);
        return apiResponse(200, 'password updated');
    }
}
