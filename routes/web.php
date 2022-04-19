<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('products', ProductController::class)->parameters([
        'products' => 'product_id'
    ])->except(['show', 'index']);

    Route::patch('products/{product_id}/pause', [ProductController::class, 'pause'])->name('products.pause');
});

Route::resource('products', ProductController::class)->parameters([
    'products' => 'product_id'
])->only(['show', 'index']);

Route::get('products-search', [ProductController::class, 'search'])->name('products.search');

Route::resource('categories', CategorieController::class);

Route::get('/history', [SearchController::class, 'historySearch'])->name('searches.history');

Route::get('/most-searched', [SearchController::class, 'mostSearched'])->name('searches.most-searched');

Route::get('/autosuggest', [SearchController::class, 'autosuggest'])->name('searches.autosuggest');
