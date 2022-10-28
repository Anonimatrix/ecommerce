<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::resource('orders', OrderController::class)->parameters([
    'orders' => 'order_id'
]);

Route::get('orders/{order_id}/pay-url', [OrderController::class, 'getPayUrl'])->name('orders.get-pay-url');

Route::get('buys', [OrderController::class, 'buys'])->name('user.buys');

Route::get('sells', [OrderController::class, 'sells'])->name('user.sells');

Route::get('/order/{order_id}/label', [OrderController::class, 'getLabel'])->name('orders.label');
