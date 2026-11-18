<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokBahanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect()->route('bahan.index');
});

Route::resource('bahan', BahanController::class)->except(['show']);
Route::resource('stok-bahan', StokBahanController::class)->except(['show']);
Route::resource('satuan', SatuanController::class)->except(['show']);
Route::resource('supplier', SupplierController::class)->except(['show']);