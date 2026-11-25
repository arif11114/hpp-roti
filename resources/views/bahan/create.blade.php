@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Tambah Bahan Baku</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bahan.index') }}">Master Bahan Baku</a></li>
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
                <h5 class="card-title font-weight-bold mb-0">Tambah Bahan Baku Baru</h5>
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

                <form action="{{ route('bahan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ old('redirect_to', $redirectTo ?? '') }}">

                    <div class="form-group">
                        <label for="nama_bahan">Nama Bahan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_bahan" id="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" value="{{ old('nama_bahan') }}" placeholder="Contoh: Tepung Terigu Protein Tinggi" required>
                        @error('nama_bahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_satuan">Satuan Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_satuan" id="id_satuan" class="form-control @error('id_satuan') is-invalid @enderror" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $satuan)
                                    <option value="{{ $satuan->id_satuan }}" {{ old('id_satuan', session('selected_satuan')) == $satuan->id_satuan ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }} ({{ $satuan->singkatan }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary font-weight-bold" data-toggle="modal" data-target="#modalTambahSatuan" title="Tambah Satuan Baru">...</button>
                            </div>
                            @error('id_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="masa_kadaluarsa_hari">Batas Kadaluarsa Normatif (Hari) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="masa_kadaluarsa_hari" id="masa_kadaluarsa_hari" class="form-control @error('masa_kadaluarsa_hari') is-invalid @enderror" value="{{ old('masa_kadaluarsa_hari', 30) }}" min="1" required>
                            <div class="input-group-append">
                                <span class="input-group-text">Hari</span>
                            </div>
                            @error('masa_kadaluarsa_hari')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Acuan masa simpan standar bahan (contoh: Tepung = 30 hari, Mentega = 14 hari, Pisang = 3 hari).</small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ $redirectTo ?? route('bahan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Bahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.quick_modals')
@endsection