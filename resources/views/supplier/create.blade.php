@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Tambah Supplier</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Master Supplier</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title font-weight-bold mb-0">Tambah Supplier Baru</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $redirectTo ?? '') }}">

                    <div class="form-group">
                        <label for="nama_supplier">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="form-control @error('nama_supplier') is-invalid @enderror"
                            placeholder="Contoh: Toko Bahan Kue Jaya" value="{{ old('nama_supplier') }}" required>
                        @error('nama_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kontak">Kontak / No. HP <small class="text-muted">(Opsional)</small></label>
                        <input type="text" name="kontak" id="kontak"
                            class="form-control @error('kontak') is-invalid @enderror"
                            placeholder="Contoh: 081234567890" value="{{ old('kontak') }}">
                        @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat <small class="text-muted">(Opsional)</small></label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Masukkan alamat lengkap supplier...">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ $redirectTo ?? route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection