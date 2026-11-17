@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Tambah Bahan Baku Baru</h5>
            </div>
            <div class="card-body">
                @if($satuans->isEmpty())
                    <div class="alert alert-warning">
                        Data satuan masih kosong. Silakan <a href="{{ route('satuan.create') }}" class="alert-link">tambah satuan terlebih dahulu</a> sebelum menambahkan bahan.
                    </div>
                @endif

                <form action="{{ route('bahan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_bahan" class="form-label">Nama Bahan</label>
                        <input type="text" name="nama_bahan" id="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" value="{{ old('nama_bahan') }}" placeholder="Contoh: Tepung Terigu Protein Tinggi" required>
                        @error('nama_bahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_satuan" class="form-label">Satuan Beli</label>
                        <select name="id_satuan" id="id_satuan" class="form-select @error('id_satuan') is-invalid @enderror" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $satuan)
                                <option value="{{ $satuan->id_satuan }}" {{ old('id_satuan') == $satuan->id_satuan ? 'selected' : '' }}>
                                    {{ $satuan->nama_satuan }} ({{ $satuan->singkatan }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('bahan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary" {{ $satuans->isEmpty() ? 'disabled' : '' }}>Simpan Bahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection