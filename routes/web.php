<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokBahanController;

Route::get('/', function () {
    return redirect()->route('bahan.index');
});

Route::resource('bahan', BahanController::class)->except(['show']);
Route::resource('stok-bahan', StokBahanController::class)->except(['show']);