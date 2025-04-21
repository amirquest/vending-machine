<?php

use App\Http\Controllers\Customer\Item\PreviewItemsController;

Route::get('/', PreviewItemsController::class)->name('preview');
