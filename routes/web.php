<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('index');
Route::post('/', [\App\Http\Controllers\MainController::class, 'login'])->name('index.login');
Route::any('logout', [\App\Http\Controllers\MainController::class, 'logout'])->name('logout');

Route::middleware([\App\Http\Middleware\LoginMiddleware::class])->group(function (){
    Route::get('/profile', [\App\Http\Controllers\MainController::class, 'indexProfile'])->name('index.profile');
    Route::get('/users', [\App\Http\Controllers\Users\UsersController::class, 'indexList'])->name('users.list');

    Route::resource('/rooms', \App\Http\Controllers\RoomsController::class);

    Route::post('training/create', [\App\Http\Controllers\trainingController::class, 'create'])->name('training.create');
    Route::post('room/calendar', [\App\Http\Controllers\RoomsController::class, 'showTable'])->name('rooms.calendar');
    Route::get('/t', function (){
        return view('test');
    });
    Route::get('/test', [\App\Http\Controllers\testcontroller::class, 'index']);
});

