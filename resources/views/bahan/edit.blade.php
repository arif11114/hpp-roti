@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Bahan Baku</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bahan.update', $bahan->id_bahan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_bahan" class="form-label">Nama Bahan</label>
                        <input type="text" name="nama_bahan" id="nama_bahan" class="form-control @error('nama_bahan') is-invalid @enderror" value="{{ old('nama_bahan', $bahan->nama_bahan) }}" required>
                        @error('nama_bahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_satuan" class="form-label">Satuan Beli</label>
                        <select name="id_satuan" id="id_satuan" class="form-select @error('id_satuan') is-invalid @enderror" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $satuan)
                                <option value="{{ $satuan->id_satuan }}" {{ old('id_satuan', $bahan->id_satuan) == $satuan->id_satuan ? 'selected' : '' }}>
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
                        <button type="submit" class="btn btn-primary">Update Bahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection