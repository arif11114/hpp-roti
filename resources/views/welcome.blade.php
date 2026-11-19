@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Dashboard Sistem HPP</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ \App\Models\SatuanModel::count() }}</h3>
                <p>Master Satuan</p>
            </div>
            <div class="icon">
                <i class="fas fa-balance-scale"></i>
            </div>
            <a href="{{ route('satuan.index') }}" class="small-box-footer">Lihat Data <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ \App\Models\BahanModel::count() }}</h3>
                <p>Master Bahan Baku</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="{{ route('bahan.index') }}" class="small-box-footer">Lihat Data <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\SupplierModel::count() }}</h3>
                <p>Master Supplier</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck"></i>
            </div>
            <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat Data <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ \App\Models\StokBahanModel::count() }}</h3>
                <p>Transaksi Stok Masuk</p>
            </div>
            <div class="icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <a href="{{ route('stok-bahan.index') }}" class="small-box-footer">Lihat Data <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-bread-slice mr-2 text-warning"></i>Selamat Datang di Sistem Perhitungan HPP Pabrik Roti</h3>
    </div>
    <div class="card-body">
        <p class="mb-0 text-muted">Gunakan menu navigasi di sebelah kiri untuk mengelola <strong>Master Satuan</strong>, <strong>Master Bahan Baku</strong>, <strong>Master Supplier</strong>, serta pencatatan <strong>Stok Bahan Masuk</strong>.</p>
    </div>
</div>
@endsection