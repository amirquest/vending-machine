<?php


Route::prefix('payments/')
    ->name('payment.')
    ->group(__DIR__ . '/payment/routes.php');

Route::prefix('orders/')
    ->name('order.')
    ->group(__DIR__ . '/order/routes.php');
