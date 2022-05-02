<?php

use App\Http\Controllers\AdressController;
use App\Http\Controllers\Auth\OAuthLoginController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubcategorieController;
use App\Http\Controllers\TagController;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

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

Route::middleware(['auth.password'])->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::resource('products', ProductController::class)->parameters([
            'products' => 'product_id'
        ])->except(['show', 'index']);

        Route::patch('products/{product_id}/pause', [ProductController::class, 'pause'])->name('products.pause');
    });

    Route::resource('products', ProductController::class)->parameters([
        'products' => 'product_id'
    ])->only(['show', 'index']);

    Route::resource('roles', RoleController::class)->parameters([
        'roles' => 'role_id'
    ])->except(['show']);

    Route::get('products-search', [ProductController::class, 'search'])->name('products.search');

    Route::resource('subcategories', SubcategorieController::class)->parameters([
        'subcategories' => 'subcategorie_id'
    ]);

    Route::resource('categories', CategorieController::class);

    Route::get('/history', [SearchController::class, 'historySearch'])->name('searches.history');

    Route::get('/most-searched', [SearchController::class, 'mostSearched'])->name('searches.most-searched');

    Route::get('/autosuggest', [SearchController::class, 'autosuggest'])->name('searches.autosuggest');

    Route::resource('adresses', AdressController::class)->middleware('auth:sanctum')->parameters([
        'adresses' => 'adress_id'
    ]);

    Route::get('/auth/{driver}/redirect',  [OAuthLoginController::class, 'redirectToProvider'])->name('oauth.redirect');

    Route::get('/auth/{driver}/callback', [OAuthLoginController::class, 'handleProviderCallback'])->name('oauth.callback');

    Route::get('/tags/suggest', [TagController::class, 'suggest'])->name('tags.suggest');
});

Route::middleware(['auth.not-password'])->group(function () {
    Route::get('/auth/set-password', [OAuthLoginController::class, 'setPasswordView'])->name('oauth.set-password-view');

    Route::post('/auth/set-password', [OAuthLoginController::class, 'setPassword'])->name('oauth.set-password');
});
