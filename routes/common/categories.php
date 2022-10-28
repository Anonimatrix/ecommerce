<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;

Route::resource('categories', CategorieController::class)->parameters([
    'categories' => 'categorie_id'
])->only('index');
