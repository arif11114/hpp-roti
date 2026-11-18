@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Master Supplier</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('supplier.update', $supplier->id_supplier) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_supplier" class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" id="nama_supplier"
                            class="form-control @error('nama_supplier') is-invalid @enderror"
                            value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                        @error('nama_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kontak" class="form-label fw-semibold">Kontak / No. HP <small class="text-muted">(Opsional)</small></label>
                        <input type="text" name="kontak" id="kontak"
                            class="form-control @error('kontak') is-invalid @enderror"
                            value="{{ old('kontak', $supplier->kontak) }}">
                        @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat <small class="text-muted">(Opsional)</small></label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $supplier->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection