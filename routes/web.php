<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\LaporanHppController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\StokBahanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;

// Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirect dashboard sesuai role
    Route::get('/', function () {
        if (auth()->user()->role === 'produksi') {
            return redirect()->route('produksi.index');
        }
        return redirect()->route('bahan.index');
    })->name('dashboard');

    // Semua role (admin, gudang, produksi) bisa melihat daftar Bahan Baku & Laporan HPP
    Route::get('bahan', [BahanController::class, 'index'])->name('bahan.index');
    Route::get('laporan/hpp', [LaporanHppController::class, 'index'])->name('laporan.hpp');

    // Admin & Gudang: Lihat Satuan & Supplier, Tambah Master baru, & Kelola Stok Bahan Masuk
    Route::middleware('role:admin,gudang')->group(function () {
        Route::get('satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::get('supplier', [SupplierController::class, 'index'])->name('supplier.index');

        Route::resource('bahan', BahanController::class)->only(['create', 'store']);
        Route::resource('satuan', SatuanController::class)->only(['create', 'store']);
        Route::resource('supplier', SupplierController::class)->only(['create', 'store']);

        Route::resource('stok-bahan', StokBahanController::class)->except(['show']);
    });

    // Admin & Produksi: Input & Lihat Transaksi Produksi (TANPA akses resep)
    Route::middleware('role:admin,produksi')->group(function () {
        Route::resource('produksi', ProduksiController::class)->only(['index', 'create', 'store', 'show']);
    });

    // Khusus Admin: Kelola Produk & Resep (Full Access), Edit/Hapus Master, Pembatalan Produksi, & Manajemen User
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('produk', ProdukController::class);

        Route::resource('bahan', BahanController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('satuan', SatuanController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('supplier', SupplierController::class)->only(['edit', 'update', 'destroy']);
        Route::delete('produksi/{produksi}', [ProduksiController::class, 'destroy'])->name('produksi.destroy');
    });
});