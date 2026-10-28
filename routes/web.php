<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MutasiStokController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pelanggan\LandingController;
use App\Http\Controllers\Pelanggan\TokoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/toko', [TokoController::class, 'index'])->name('toko.index');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'indexLogin'])->name('login');
        Route::post('/masuk', [AuthController::class, 'login'])->middleware('throttle:20,1')->name('login.submit');
        Route::get('/daftar', [AuthController::class, 'indexRegister'])->name('register');
        Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.submit');
    });
    Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
});

Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('produk', ProdukController::class);
    Route::resource('mutasi-stok', MutasiStokController::class);
    Route::resource('transaksi', TransaksiController::class)->only(['index','show','edit','update','destroy',]);
    Route::resource('pengiriman', PengirimanController::class)->only(['index','show','edit','update','destroy',]);
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('pengguna', PenggunaController::class)->except('show');
    });
});