<?php

use App\Http\Controllers\PaymentController;

Route::prefix('payment')->name('payment.')->group(function () {
    Route::match(['GET', 'POST'], 'callback', [PaymentController::class, 'callBack'])->name('callback');
    Route::get('success', [PaymentController::class, 'success'])->name('success');
    Route::get('failed', [PaymentController::class, 'failed'])->name('failed');
});