@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Satuan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('satuan.update', $satuan->id_satuan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_satuan" class="form-label">Nama Satuan</label>
                        <input type="text" name="nama_satuan" id="nama_satuan" class="form-control @error('nama_satuan') is-invalid @enderror" value="{{ old('nama_satuan', $satuan->nama_satuan) }}" required>
                        @error('nama_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="singkatan" class="form-label">Singkatan</label>
                        <input type="text" name="singkatan" id="singkatan" class="form-control @error('singkatan') is-invalid @enderror" value="{{ old('singkatan', $satuan->singkatan) }}" required>
                        @error('singkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Update Satuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection