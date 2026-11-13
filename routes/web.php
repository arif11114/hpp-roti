<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanController;

// Saat membuka halaman awal (http://127.0.0.1:8000), langsung arahkan ke daftar bahan
Route::get('/', function () {
    return redirect()->route('bahan.index');
});

// Otomatis mendaftarkan semua rute CRUD (index, create, store, edit, update, destroy) untuk Bahan
Route::resource('bahan', BahanController::class)->except(['show']);