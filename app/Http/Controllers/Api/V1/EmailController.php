<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\verifyEmailRequest;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Ichtrojan\Otp\Otp;

class EmailController extends Controller
{
    protected $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function sendOtp(SendOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->hasVerifiedEmail()) {
            return apiResponse(200, 'Email already verified.');
        }
        $user->notify(new VerifyEmailNotification());
        return apiResponse(200, 'Verification code resent.');
    }
    public function verifyEmail(verifyEmailRequest $request)
    {
        $otp = new Otp();
        $validation = $otp->validate($request->email, $request->otp);
        if (! $validation->status) {
            return apiResponse(400, 'Invalid or expired verification code.');
        }
        $user = User::where('email', $request->email)->first();
        $user->update(['email_verified_at' => now()]);
        return apiResponse(200, 'Email verified successfully.');
    }
}
