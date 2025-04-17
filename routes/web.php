<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'power_by' => sprintf('© %s Dara Tech. Team.', now()->year),
        'message' => 'Every Services are available',
        'result' => [
            'version' => '1.0.0',
            'web_app' => 'https://dpbclinic.com/'
        ],
    ]);
});


Route::view('/ipg/result/undefined', 'undefined-transaction')->name('transaction.undefined');
