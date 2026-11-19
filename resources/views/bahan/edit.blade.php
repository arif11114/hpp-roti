@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Edit Bahan Baku</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bahan.index') }}">Master Bahan Baku</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h5 class="card-title font-weight-bold mb-0">Edit Bahan Baku</h5>
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

                <form action="{{ route('bahan.update', $bahan->id_bahan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_bahan">Nama Bahan</label>
                        <input type="text" name="nama_bahan" id="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" value="{{ old('nama_bahan', $bahan->nama_bahan) }}" required>
                        @error('nama_bahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_satuan">Satuan Beli</label>
                        <div class="input-group">
                            <select name="id_satuan" id="id_satuan" class="form-control @error('id_satuan') is-invalid @enderror" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $satuan)
                                    <option value="{{ $satuan->id_satuan }}" {{ old('id_satuan', session('selected_satuan', $bahan->id_satuan)) == $satuan->id_satuan ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }} ({{ $satuan->singkatan }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <a href="{{ route('satuan.create', ['redirect_to' => url()->current()]) }}" class="btn btn-outline-secondary font-weight-bold" title="Tambah Satuan Baru">...</a>
                            </div>
                            @error('id_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('bahan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Bahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection