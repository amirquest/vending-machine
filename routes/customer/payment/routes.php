<?php

use App\Http\Controllers\Customer\Payment\PaymentController;

Route::post('pay', PaymentController::class)->name('pay');
