@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-warning">Edit Data Bahan</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('bahan.update', $bahan->id_bahan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_bahan" class="form-label fw-semibold">Nama Bahan</label>
                        <input type="text" 
                               name="nama_bahan" 
                               id="nama_bahan" 
                               class="form-control @error('nama_bahan') is-invalid @enderror" 
                               value="{{ old('nama_bahan', $bahan->nama_bahan) }}" 
                               required autofocus>

                        @error('nama_bahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bahan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Bahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection