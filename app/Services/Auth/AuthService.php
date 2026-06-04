<?php

namespace App\Services\Auth;

use App\Exceptions\EmailNotVerifiedException;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use App\DTOs\AuthResult;

class AuthService
{
    public function register(array $data): AuthResult
    {
        $user = User::create($data);
        $user->notify(new VerifyEmailNotification());
        return new AuthResult(
            $user,
            $this->createToken($user)
        );
    }
    public function login(string $email, string $password): AuthResult
    {
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            throw new AuthenticationException(__('auth.failed'));
        }
        if (!$user->hasVerifiedEmail()) {
            throw new EmailNotVerifiedException();
        }
        return new AuthResult(
            $user,
            $this->createToken($user)
        );
    }
    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
    private function createToken(User $user): string
    {
        return $user->createToken('auth_token', ['*'], now()->addWeek())->plainTextToken;
    }

}
