<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', [\App\Http\Controllers\testcontroller::class, 'index']);
Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('index');

Route::resource('/rooms', \App\Http\Controllers\RoomsController::class);
