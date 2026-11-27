<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'loginApi']);
Route::post('/register', [LoginController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users-list', [UserController::class, 'getData'])->name('list-users');
    Route::apiResource('users', UserController::class);
    Route::post('/logout', [LoginController::class, 'logoutApi']);
});