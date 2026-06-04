<?php

namespace App\Services\Auth;

use App\Exceptions\InvalidOtpException;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use App\Exceptions\EmailAlreadyVerifiedException;
use Exception;
use Ichtrojan\Otp\Otp;

class EmailVerificationService
{
    public function sendOtp(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }
        $user->notify(new VerifyEmailNotification());
    }
    public function verifyEmail(User $user, string $otp): void
    {
        $validation = (new Otp)->validate($user->email, $otp);

        if (!$validation->status) {
            throw new InvalidOtpException();
        }
        $user->markEmailAsVerified();
    }
}
