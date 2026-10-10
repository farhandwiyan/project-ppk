<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\FasilitasPublicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\PetugasLaporanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReportController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/fasilitas', [FasilitasPublicController::class, 'index'])->name('fasilitas-public.index');
Route::get('/fasilitas/{fasilitas}', [FasilitasPublicController::class, 'show'])->name('fasilitas.show');


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
    
    Route::get('/users/update', [UserController::class, 'showUpdateForm'])->name('update-user');
    Route::put('users/update', [UserController::class, 'update'])->name('updateUser');

    // role: admin
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'homeAdmin'])->name('admin.home');
        
        Route::get('admin/users', [DashboardController::class, 'showAllUser'])->name('users.index');
        Route::post('/verifiedUser', [UserController::class, 'verified'])->name('verifiedUser');
        Route::delete('/deleteUser', [UserController::class, 'delete'])->name('deleteUser');
        Route::get('/users/create', [UserController::class, 'showCreateForm'])->name('create-user');
        Route::post('/user/create', [UserController::class, 'create'])->name('createUser');
        
        Route::get('admin/fasilitas', [DashboardController::class, 'showAllFasilitas'])->name('fasilitas.index');
        Route::get('admin/fasilitas/create', [FasilitasController::class, 'showCreateForm'])->name('fasilitas.create');
        Route::post('admin/fasilitas', [FasilitasController::class, 'create'])->name('fasilitas.store');
        Route::get('admin/fasilitas/{fasilitas}/edit', [FasilitasController::class, 'showEditForm'])->name('fasilitas.edit');
        Route::put('admin/fasilitas/{fasilitas}', [FasilitasController::class, 'update'])->name('fasilitas.update');
        Route::delete('admin/fasilitas/{fasilitas}', [FasilitasController::class, 'delete'])->name('fasilitas.delete');
        Route::patch('admin/fasilitas/{fasilitas}/status', [FasilitasController::class, 'updateStatus'])->name('fasilitas.update-status');

        Route::get('admin/laporan/okupansi', [ReportController::class, 'okupansi'])->name('admin.laporan.okupansi');
    Route::get('admin/laporan/okupansi/export', [ReportController::class, 'exportOkupansi'])->name('admin.laporan.okupansi.export');
    
    // 2. Rekap Kerusakan & Export
    Route::get('admin/laporan/kerusakan', [ReportController::class, 'kerusakan'])->name('admin.laporan.kerusakan');
    Route::get('admin/laporan/kerusakan/export', [ReportController::class, 'exportKerusakan'])->name('admin.laporan.kerusakan.export');

    });

    // role: petugas
    Route::middleware('role:petugas')->group(function () {
        Route::get('petugas/dashboard', [PetugasDashboardController::class, 'index'])->name('petugas.home');
        Route::get('petugas/reservasi/{id}', [PetugasDashboardController::class, 'show'])->name('petugas.reservasi.show');
        
        Route::patch('petugas/reservasi/{id}/setuju', [PetugasDashboardController::class, 'setuju'])->name('petugas.reservasi.setuju');
        Route::patch('petugas/reservasi/{id}/tolak', [PetugasDashboardController::class, 'tolak'])->name('petugas.reservasi.tolak');

        Route::get('petugas/riwayat', [PetugasDashboardController::class, 'riwayat'])->name('petugas.riwayat');
        Route::delete('petugas/riwayat/{id}', [PetugasDashboardController::class, 'destroyRiwayat'])->name('petugas.riwayat.destroy');

        Route::get('petugas/laporan', [PetugasLaporanController::class, 'index'])->name('petugas.laporan.index');
        Route::get('petugas/laporan/{id}', [PetugasLaporanController::class, 'show'])->name('petugas.laporan.show');
        Route::patch('petugas/laporan/{id}/status', [PetugasLaporanController::class, 'updateStatus'])->name('petugas.laporan.status');
    });

    // role: user

    Route::middleware('role:user')->group(function () {
        Route::get('/fasilitas/{fasilitas}/reservasi', [ReservationController::class, 'showCreateForm'])->name('reservations.create');
        Route::post('/fasilitas/{fasilitas}/reservasi', [ReservationController::class, 'create'])->name('reservations.store');
        Route::get('/reservasi/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    
        Route::get('/reservasi', [ReservationController::class, 'riwayat'])->name('reservations.riwayat');
        Route::get('reservasi/{reservation}', [ReservationController::class, 'showDetail'])->name('reservations.detail');
        Route::patch('/reservasi/{reservation}', [ReservationController::class, 'cancel'])->name('reservations.cancel');
      
        Route::get('/laporan-kerusakan', [LaporanKerusakanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan-kerusakan', [LaporanKerusakanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{laporan}', [LaporanKerusakanController::class, 'show'])->name('laporan.show');
    });
});
