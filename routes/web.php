<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('index');
Route::post('/', [\App\Http\Controllers\MainController::class, 'login'])->name('index.login');
Route::any('logout', [\App\Http\Controllers\MainController::class, 'logout'])->name('logout');

Route::middleware([\App\Http\Middleware\LoginMiddleware::class])->group(function (){
    Route::get('/profile', [\App\Http\Controllers\MainController::class, 'indexProfile'])->name('index.profile');
    Route::post('/profile/update/password', [\App\Http\Controllers\Users\UsersController::class, 'updatePassword'])->name('password.update');
    Route::middleware([\App\Http\Middleware\AdminRole::class])->group(function (){
        Route::get('/users', [\App\Http\Controllers\Users\UsersController::class, 'indexList'])->name('users.list');
        Route::post('/users/list', [\App\Http\Controllers\Users\UsersController::class, 'usersList'])->name('get.users.list');
        Route::post('/users/create', [\App\Http\Controllers\Users\UsersController::class, 'createUsers'])->name('users.create');
        Route::get('/users/profile/{id}', [\App\Http\Controllers\Users\UsersController::class, 'showUsers'])->name('users.show');

        Route::resource('/rooms', \App\Http\Controllers\RoomsController::class);

        Route::post('training/create', [\App\Http\Controllers\trainingController::class, 'create'])->name('training.create');
        Route::post('training/delete', [\App\Http\Controllers\trainingController::class, 'delete'])->name('training.delete');
        Route::post('training/edit', [\App\Http\Controllers\trainingController::class, 'edit'])->name('training.edit');
        Route::post('room/calendar', [\App\Http\Controllers\RoomsController::class, 'showTable'])->name('rooms.calendar');

        Route::get('groups', [\App\Http\Controllers\GroupsController::class, 'index'])->name('index.group');
    });
    Route::get('training/list', [\App\Http\Controllers\trainingController::class, 'trainingProfile'])->name('training.profile');
    Route::get('trainings', [\App\Http\Controllers\trainingController::class, 'trainingList'])->name('training.list');
    Route::post('training/create/coach', [\App\Http\Controllers\trainingController::class, 'createTrainingCoach'])->name('training.coach.create');
    Route::get('training/groups', [\App\Http\Controllers\trainingController::class, 'groupsIndex'])->name('groups.index');
    Route::post('training/groups', [\App\Http\Controllers\trainingController::class, 'groupsCreate'])->name('groups.create');
    Route::post('training/addTraing', [\App\Http\Controllers\trainingController::class, 'addTraing'])->name('add.traing');
    Route::get('/t', function (){
        return view('test');
    });
    Route::get('/test', [\App\Http\Controllers\testcontroller::class, 'index']);
});

