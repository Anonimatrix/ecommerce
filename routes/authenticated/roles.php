<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::resource('roles', RoleController::class)->parameters([
    'roles' => 'role_id'
])->except(['show']);
