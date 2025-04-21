
<?php

use App\Http\Controllers\CallbackController;

Route::post('callback/{identifier}/ready', CallbackController::class)->name('callback.ready');
