<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidOtpException;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use Ichtrojan\Otp\Otp;

class PasswordService
{
    public function forgetPassword(string $identifier): void
    {
        $user = User::where('email', $identifier)->orWhere('mobile', $identifier)->firstOrFail();
        $user->notify(new ForgetPasswordNotification());
    }
    public function resetPassword(string $email, string $otp, string $password): void
    {
        $otpValidation = (new Otp())->validate($email, $otp);
        if (!$otpValidation->status) {
            throw new InvalidOtpException();
        }
        User::where('email', $email)->firstOrFail()->update(['password' => $password]);
    }
}
