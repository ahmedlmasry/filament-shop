<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\PasswordController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'setLang'], function () {
    //  users
    Route::group(['prefix' => 'user'], function () {
        //auth
        Route::controller(AuthController::class)->group(function () {
            Route::post('login',  'login');
            Route::post('register', 'register');
            Route::post('logout', 'logout')->middleware('auth:sanctum');
        });
        //password
        Route::controller(PasswordController::class)->group(function () {
            Route::post('forget-password',  'forgetPassword');
            Route::post('reset-password',  'resetPassword');
        });
        //email
        Route::controller(EmailController::class)->group(function () {
            Route::post('send-otp',  'sendOtp');
            Route::post('verify-email',  'verifyEmail');
        });
    });

    // General
    Route::get('cities', [GeneralController::class, 'getCities']);
    Route::get('categories', [GeneralController::class, 'getCategories']);
    Route::get('brands', [GeneralController::class, 'getBrands']);
});
