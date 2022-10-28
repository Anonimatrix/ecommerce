<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShippController;

Route::get('shipping/{product_id}/shipp-quote', [ShippController::class, 'quote'])->name('shipp.quote');
Route::get('shipping/{adress_id}/shipp-list-sucursales', [ShippController::class, 'listSucursales'])->name('shipp.list-sucursales');
