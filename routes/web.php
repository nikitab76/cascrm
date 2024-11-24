<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('index');
Route::post('/', [\App\Http\Controllers\MainController::class, 'login'])->name('index.login');
Route::any('logout', [\App\Http\Controllers\MainController::class, 'logout'])->name('logout');

Route::middleware([\App\Http\Middleware\LoginMiddleware::class])->group(function (){
    Route::get('/profile', [\App\Http\Controllers\MainController::class, 'indexProfile'])->name('index.profile');
    Route::get('/users', [\App\Http\Controllers\Users\UsersController::class, 'indexList'])->name('users.list');
    Route::post('/users/create', [\App\Http\Controllers\Users\UsersController::class, 'createUsers'])->name('users.create');
    Route::get('/users/profile/{id}', [\App\Http\Controllers\Users\UsersController::class, 'showUsers'])->name('users.show');

    Route::resource('/rooms', \App\Http\Controllers\RoomsController::class);

    Route::post('training/create', [\App\Http\Controllers\trainingController::class, 'create'])->name('training.create');
    Route::post('training/delete', [\App\Http\Controllers\trainingController::class, 'delete'])->name('training.delete');
    Route::post('training/edit', [\App\Http\Controllers\trainingController::class, 'edit'])->name('training.edit');
    Route::post('room/calendar', [\App\Http\Controllers\RoomsController::class, 'showTable'])->name('rooms.calendar');

    Route::get('training/list', [\App\Http\Controllers\trainingController::class, 'trainingProfile'])->name('training.profile');
    Route::post('training/create/coach', [\App\Http\Controllers\trainingController::class, 'createTrainingCoach'])->name('training.coach.create');
    Route::get('/t', function (){
        return view('test');
    });
    Route::get('/test', [\App\Http\Controllers\testcontroller::class, 'index']);
});

