@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Edit Stok Bahan</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('stok-bahan.update', $stokBahan->id_stok) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="id_bahan" class="form-label">Nama Bahan</label>
                        <select name="id_bahan" id="id_bahan" class="form-select" required>
                            <option value="">-- Pilih Bahan Baku --</option>
                            @foreach($bahans as $bahan)
                                <option value="{{ $bahan->id_bahan }}" {{ old('id_bahan', $stokBahan->id_bahan) == $bahan->id_bahan ? 'selected' : '' }}>
                                    {{ $bahan->nama_bahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_belanja" class="form-label">Tanggal Belanja</label>
                            <input type="date" name="tanggal_belanja" id="tanggal_belanja" class="form-control" value="{{ old('tanggal_belanja', $stokBahan->tanggal_belanja) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_gram" class="form-label">Jumlah (dalam Gram)</label>
                            <input type="number" step="0.01" name="jumlah_gram" id="jumlah_gram" class="form-control" value="{{ old('jumlah_gram', $stokBahan->jumlah_gram) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli (Rp)</label>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', $stokBahan->harga_beli) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_expired" class="form-label">Tanggal Expired</label>
                            <input type="date" name="tanggal_expired" id="tanggal_expired" class="form-control" value="{{ old('tanggal_expired', $stokBahan->tanggal_expired) }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="batas_notif_expired" class="form-label">Batas Notif Expired (Hari)</label>
                            <input type="number" name="batas_notif_expired" id="batas_notif_expired" class="form-control" value="{{ old('batas_notif_expired', $stokBahan->batas_notif_expired) }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('stok-bahan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection