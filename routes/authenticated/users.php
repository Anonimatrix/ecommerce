<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::resource('users', UserController::class)->parameters([
    'users' => 'user_id'
])->only(['index']);

Route::patch('users/{user_id}/ban', [UserController::class, 'ban'])->name('users.ban');

Route::patch('users/{user_id}/assing-roles', [UserController::class, 'assignRoles'])
    ->name('users.assign-roles');

Route::get('users/{user_id}/assign-roles', [UserController::class, 'assignRolesPage'])
    ->name('users.assign-roles-page');

Route::get('user/money', [UserController::class, 'getMoney'])->name('user.money');

Route::get('withdraw', [UserController::class, 'withdraw'])->name('user.withdraw');
