<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;

Route::resource('chats', ChatController::class)->parameters([
    'chats' => 'chat_id'
])->only('show');

Route::post('messages/{chat_id}/store', [MessageController::class, 'store'])
    ->name('messages.store');

Route::delete('messages/{message_id}', [MessageController::class, 'destroy'])
    ->name('messages.destroy');
