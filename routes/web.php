<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page.home');
});

Route::get('/profile', [MahasiswaController::class, 'index']);

Route::get('/project', [ProjectController::class, 'index'])
    ->name('project.index');

Route::get('/project/{id}', [ProjectController::class, 'show'])
    ->name('project.show');

Route::get('/about', function () {
    return view('page.about');
});

// ADMIN DASHBOARD
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])
        ->name('home');

    Route::get('/create', [AdminController::class, 'create'])
        ->name('create');

    Route::post('/store', [AdminController::class, 'store'])
        ->name('store');

    Route::get('/edit/{id}', [AdminController::class, 'edit'])
        ->name('edit');

    Route::put('/update/{id}', [AdminController::class, 'update'])
        ->name('update');

    Route::delete('/delete/{id}', [AdminController::class, 'destroy'])
        ->name('destroy');

});