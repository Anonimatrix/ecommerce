<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubcategorieController;

Route::resource('subcategories', SubcategorieController::class)->parameters([
    'subcategories' => 'subcategorie_id'
])->except('show');
