<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return apiResponse(401,__('auth.failed'));
        }
        return apiResponse(200, __('auth.login'), [
            'user' => new UserResource($user),
            'token' => $user->createToken('auth_token', ['*'], now()->addWeek())->plainTextToken
        ]);
    }
    public function register(StoreUserRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            return User::create($request->validated());
        });
        $user->notify(new VerifyEmailNotification());
        return apiResponse(201, 'Registered successfully. Verification code sent to email.', [
            'user' => new UserResource($user),
            'token' => $user->createToken('auth_token', ['*'], now()->addWeek())->plainTextToken
        ]);
    }
    public function logout (Request $request)
    {
        $request->user()->tokens()->delete();
        return apiResponse(200,__('auth.logout'));
    }
}
