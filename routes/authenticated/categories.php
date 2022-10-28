<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategorieController;

Route::patch(
    'categories/{categorie_id}/subcategorie-move',
    [CategorieController::class, 'moveSubcategoriesToOtherCategorie']
)->name('categories.subcategorie-move');

Route::delete(
    'categories/{categorie_id}/subcategorie-remove',
    [CategorieController::class, 'removeAllForCategorie']
)->name('categories.subcategorie-remove');

Route::resource('categories', CategorieController::class)->parameters([
    'categories' => 'categorie_id'
])->except('index');
