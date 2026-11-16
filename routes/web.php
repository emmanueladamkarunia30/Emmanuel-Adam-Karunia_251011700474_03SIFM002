<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// HALAMAN PUBLIK
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

// LOGIN / LOGOUT
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ADMIN (wajib login)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Admin + Editor
    Route::middleware(RoleMiddleware::class . ':admin,editor')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('home');
        Route::get('/projects/pdf', [AdminController::class, 'exportPdf'])->name('projects.pdf');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminController::class, 'update'])->name('update');
    });

    // Khusus Admin
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::delete('/delete/{id}', [AdminController::class, 'destroy'])->name('destroy');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
});