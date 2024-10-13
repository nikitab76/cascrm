<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', [\App\Http\Controllers\testcontroller::class, 'index']);
Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('index');

Route::resource('/rooms', \App\Http\Controllers\RoomsController::class);

Route::post('training/create', [\App\Http\Controllers\trainingController::class, 'create'])->name('training.create');
Route::post('room/calendar', [\App\Http\Controllers\RoomsController::class, 'showTable'])->name('rooms.calendar');
Route::get('/t', function (){
    return view('test');
});
