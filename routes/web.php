<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MainController::class, 'index']);

Route::resource('/rooms', \App\Http\Controllers\RoomsController::class);
