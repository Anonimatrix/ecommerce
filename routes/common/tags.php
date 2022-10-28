<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;

Route::get('/tags/suggest', [TagController::class, 'suggest'])->name('tags.suggest');
