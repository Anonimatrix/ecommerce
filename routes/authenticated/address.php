<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;

Route::resource('addresses', AddressController::class)->parameters([
    'addresses' => 'address_id'
]);
