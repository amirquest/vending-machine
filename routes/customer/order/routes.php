<?php

use App\Http\Controllers\Customer\Order\OrderController;

Route::post('/', OrderController::class)->name('order');
