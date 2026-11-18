@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0 fw-bold">Edit Transaksi Stok Bahan</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

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

                    {{-- 1. Nama Bahan & Supplier + Tombol Smart Redirect (...) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_bahan" class="form-label fw-semibold">Nama Bahan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="id_bahan" id="id_bahan" class="form-select" required>
                                    <option value="" data-satuan="Satuan">-- Pilih Bahan Baku --</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id_bahan }}"
                                            data-satuan="{{ $bahan->satuan->singkatan ?? $bahan->satuan->nama_satuan ?? 'Unit' }}"
                                            {{ old('id_bahan', session('selected_bahan', $stokBahan->id_bahan)) == $bahan->id_bahan ? 'selected' : '' }}>
                                            {{ $bahan->nama_bahan }} ({{ $bahan->satuan->singkatan ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('bahan.create', ['redirect_to' => url()->current()]) }}" class="btn btn-outline-secondary fw-bold" title="Tambah Bahan Baru">...</a>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_supplier" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="id_supplier" id="id_supplier" class="form-select" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id_supplier }}"
                                            {{ old('id_supplier', session('selected_supplier', $stokBahan->id_supplier)) == $supplier->id_supplier ? 'selected' : '' }}>
                                            {{ $supplier->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                                <a href="{{ route('supplier.create', ['redirect_to' => url()->current()]) }}" class="btn btn-outline-secondary fw-bold" title="Tambah Supplier Baru">...</a>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Tanggal Beli, Tanggal Produksi & Tanggal Expired Berdampingan --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_transaksi" class="form-label fw-semibold">Tanggal Beli <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control"
                                value="{{ old('tanggal_transaksi', $stokBahan->tanggal_transaksi) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tanggal_produksi" class="form-label fw-semibold">Tanggal Produksi <small class="text-muted">(Opsional)</small></label>
                            <input type="date" name="tanggal_produksi" id="tanggal_produksi" class="form-control"
                                value="{{ old('tanggal_produksi', $stokBahan->tanggal_produksi) }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tanggal_expired" class="form-label fw-semibold">Tanggal Expired <small class="text-muted">(Opsional)</small></label>
                            <input type="date" name="tanggal_expired" id="tanggal_expired" class="form-control"
                                value="{{ old('tanggal_expired', $stokBahan->tanggal_expired) }}">
                        </div>
                    </div>

                    {{-- 3. Jumlah (mengikuti satuan bahan), Harga Beli & Batas Notif --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="jumlah" id="jumlah" class="form-control"
                                    value="{{ old('jumlah', $stokBahan->jumlah) }}" required>
                                <span class="input-group-text bg-light fw-bold" id="label-satuan">Satuan</span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="harga_beli" class="form-label fw-semibold">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control"
                                value="{{ old('harga_beli', $stokBahan->harga_beli) }}" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="batas_notif_expired" class="form-label fw-semibold">Batas Notif Expired (Hari)</label>
                            <input type="number" name="batas_notif_expired" id="batas_notif_expired" class="form-control"
                                value="{{ old('batas_notif_expired', $stokBahan->batas_notif_expired ?? 14) }}" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="{{ route('stok-bahan.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectBahan = document.getElementById('id_bahan');
        const labelSatuan = document.getElementById('label-satuan');
        const inputTglBeli = document.getElementById('tanggal_transaksi');
        const inputTglProduksi = document.getElementById('tanggal_produksi');
        const inputTglExpired = document.getElementById('tanggal_expired');

        function updateSatuan() {
            const selectedOption = selectBahan.options[selectBahan.selectedIndex];
            labelSatuan.textContent = selectedOption.getAttribute('data-satuan') || 'Satuan';
        }

        function syncTanggal() {
            const tglBeli = inputTglBeli.value;
            if (tglBeli) {
                inputTglExpired.min = tglBeli;
                inputTglProduksi.max = tglBeli;
            }
        }

        selectBahan.addEventListener('change', updateSatuan);
        inputTglBeli.addEventListener('change', syncTanggal);

        updateSatuan();
        syncTanggal();
    });
</script>
@endsection