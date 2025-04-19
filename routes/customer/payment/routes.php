<?php

use App\Http\Controllers\Customer\Payment\PurchasePaymentController;

Route::post('pay', PurchasePaymentController::class)->name('pay');
