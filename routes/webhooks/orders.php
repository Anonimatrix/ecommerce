<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('orders/paid-webhook', [OrderController::class, 'paidWebhook'])->name('orders.paid-webhook');
