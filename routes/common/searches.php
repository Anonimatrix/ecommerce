<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/history', [SearchController::class, 'historySearch'])->name('searches.history');

Route::get('/most-searched', [SearchController::class, 'mostSearched'])->name('searches.most-searched');

Route::get('/autosuggest', [SearchController::class, 'autosuggest'])->name('searches.autosuggest');
