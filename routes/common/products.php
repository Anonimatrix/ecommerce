<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class)->parameters([
    'products' => 'product_id'
])->only(['index']);

Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('products-search', [ProductController::class, 'search'])->name('products.search');

Route::get('products/{user_id}/seller', [ProductController::class, 'sellerProducts'])->name('products.seller-products');
