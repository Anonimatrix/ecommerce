<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;

Route::resource('views', ViewController::class)->only(['store']);
