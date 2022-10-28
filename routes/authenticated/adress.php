<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdressController;

Route::resource('adresses', AdressController::class)->parameters([
    'adresses' => 'adress_id'
]);
