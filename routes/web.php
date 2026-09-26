<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
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

    // admin
    Route::get('admin/dashboard', [DashboardController::class, 'homeAdmin'])->name('admin.home');
    
    Route::get('admin/users', [DashboardController::class, 'showAllUser'])->name('users.index');
    Route::post('/verifiedUser', [UserController::class, 'verified'])->name('verifiedUser');
    Route::delete('/deleteUser', [UserController::class, 'delete'])->name('deleteUser');
    Route::get('/users/create', [UserController::class, 'showCreateForm'])->name('create-user');
    Route::post('/user/create', [UserController::class, 'create'])->name('createUser');
    Route::get('/users/update', [UserController::class, 'showUpdateForm'])->name('update-user');
    Route::put('users/update', [UserController::class, 'update'])->name('updateUser');
    
    Route::get('admin/fasilitas', [DashboardController::class, 'showAllFasilitas'])->name('fasilitas.index');
    Route::get('admin/fasilitas/create', [FasilitasController::class, 'showCreateForm'])->name('fasilitas.create');
    Route::post('admin/fasilitas', [FasilitasController::class, 'create'])->name('fasilitas.store');
    Route::get('admin/fasilitas/{fasilitas}/edit', [FasilitasController::class, 'showEditForm'])->name('fasilitas.edit');
    Route::put('admin/fasilitas/{fasilitas}', [FasilitasController::class, 'update'])->name('fasilitas.update');
    Route::delete('admin/fasilitas/{fasilitas}', [FasilitasController::class, 'delete'])->name('fasilitas.delete');

    // petugas
    Route::get('petugas/dashboard', [DashboardController::class, 'homePetugas'])->name('petugas.home');

});
