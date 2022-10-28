<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OAuthLoginController;

Route::get('/auth/{driver}/redirect',  [OAuthLoginController::class, 'redirectToProvider'])->name('oauth.redirect');

Route::get('/auth/{driver}/callback', [OAuthLoginController::class, 'handleProviderCallback'])->name('oauth.callback');
