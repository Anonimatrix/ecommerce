<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class)->parameters([
    'products' => 'product_id'
])->except(['show', 'index', 'edit']);

Route::get('products/{slug}/edit', [ProductController::class, 'edit'])->name('products.edit');

Route::patch('products/{product_id}/pause', [ProductController::class, 'pause'])->name('products.pause');

Route::get('products/{product_id}/checkout', [ProductController::class, 'checkout'])->name('products.checkout');

Route::get('products/own', [ProductController::class, 'ownProducts'])->name('products.own');
