<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokBahanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;

// Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('bahan.index');
    })->name('dashboard');

    // Semua role (admin, gudang, produksi) bisa melihat daftar Bahan Baku
    Route::get('bahan', [BahanController::class, 'index'])->name('bahan.index');

    // Admin & Gudang: Lihat Satuan & Supplier, Tambah Master baru, & Kelola Stok Bahan Masuk
    Route::middleware('role:admin,gudang')->group(function () {
        Route::get('satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::get('supplier', [SupplierController::class, 'index'])->name('supplier.index');

        Route::resource('bahan', BahanController::class)->only(['create', 'store']);
        Route::resource('satuan', SatuanController::class)->only(['create', 'store']);
        Route::resource('supplier', SupplierController::class)->only(['create', 'store']);

        Route::resource('stok-bahan', StokBahanController::class)->except(['show']);
    });

    // Khusus Admin: Edit, Update, dan Hapus Master Data
    Route::middleware('role:admin')->group(function () {
        Route::resource('bahan', BahanController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('satuan', SatuanController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('supplier', SupplierController::class)->only(['edit', 'update', 'destroy']);
    });
});