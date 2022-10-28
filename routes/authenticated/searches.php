<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/history-page', [SearchController::class, 'historyPage'])
    ->name('searches.history-page');
