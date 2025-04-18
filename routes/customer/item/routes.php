<?php

use App\Http\Controllers\Customer\Item\ItemController;

Route::get('/', [ItemController::class, 'index'])->name('index');
