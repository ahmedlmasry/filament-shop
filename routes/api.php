<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\EmailVerificationController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PasswordController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ShopController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\V1\WishlistController;

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'setLang'], function () {
    //  users
    Route::group(['prefix' => 'user'], function () {
        //auth
        Route::controller(AuthController::class)->group(function () {
            Route::post('login', 'login')->middleware('throttle:login');
            Route::post('register', 'register')->middleware('throttle:register');
            Route::post('logout', 'logout')->middleware('auth:sanctum');
        });
        //email
        Route::controller(EmailVerificationController::class)->middleware(['auth:sanctum', 'throttle:otp'])->group(function () {
            Route::post('send-otp', 'sendOtp');
            Route::post('verify-email', 'verifyEmail');
        });
        //password
        Route::controller(PasswordController::class)->group(function () {
            Route::post('forget-password', 'forgetPassword');
            Route::post('reset-password', 'resetPassword');
        });
        //Wishlist
        Route::controller(WishlistController::class)->prefix('wishlist')->middleware('auth:sanctum')->group(function () {
            Route::get('/', 'getAll');
            Route::post('/{product}', 'toggle');
            Route::delete('/', 'removeAll');
        });
        //cart
        Route::controller(CartController::class)->prefix('cart')->middleware('auth:sanctum')->group(function () {
            Route::get('/', 'getAll');
            Route::post('coupon','applyCoupon');
            Route::delete('coupon','removeCoupon');
            Route::post('/{product}', 'addToCart');
            Route::patch('/{item}', 'updateQuantity');
            Route::delete('/{item}', 'remove');
            Route::delete('/', 'clear');
        });
    });

    // General
    Route::get('/home', [HomeController::class, 'getHomeData']);
    Route::get('/shop', [ShopController::class, 'getProducts']);
    Route::get('/shop/{product}', [ShopController::class, 'show']);

    Route::get('/products/{product}', [ProductController::class, 'show']);






    // Route::post('/payment/process', [PaymentController::class, 'paymentProcess']);
    // Route::get('/payment/callback', [PaymentController::class, 'callBack']);
});
