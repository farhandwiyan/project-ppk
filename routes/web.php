<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// guest
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [UserController::class, 'showAllUser'])->name('dashboard');

    Route::post('/verifiedUser', [UserController::class, 'verified'])->name('verifiedUser');
    Route::delete('/deleteUser', [UserController::class, 'delete'])->name('deleteUser');

    Route::get('/users/create', [UserController::class, 'showCreateForm'])->name('create-user');
    Route::post('/user/create', [UserController::class, 'create'])->name('createUser');

    Route::get('/users/update', [UserController::class, 'showUpdateForm'])->name('update-user');
    Route::put('users/update', [UserController::class, 'update'])->name('updateUser');
});
