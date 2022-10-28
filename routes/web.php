<?php

use App\Http\Controllers\AdressController;
use App\Http\Controllers\Auth\OAuthLoginController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShippController;
use App\Http\Controllers\SubcategorieController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
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

require __DIR__ . './webhooks/orders.php';

Route::middleware(['auth.info'])->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        require_dir(realpath(__DIR__ . '/authenticated'));
    });

    require_dir(realpath(__DIR__ . '/common'));
});

Route::middleware(['auth.not-info'])->group(function () {
    Route::get('/auth/set-info', [OAuthLoginController::class, 'setInfoView'])->name('auth.set-info-view');

    Route::post('/auth/set-info', [OAuthLoginController::class, 'setInfo'])->name('auth.set-info');
});
