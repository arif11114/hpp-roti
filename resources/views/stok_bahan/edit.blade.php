@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Edit Stok Bahan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('stok-bahan.index') }}">Stok Bahan Baku</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h5 class="card-title font-weight-bold mb-0">Edit Transaksi Stok Bahan</h5>
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('stok-bahan.update', $stokBahan->id_stok) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- 1. Nama Bahan & Supplier + Tombol Smart Redirect (...) --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_bahan">Nama Bahan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="id_bahan" id="id_bahan" class="form-control" required>
                                        <option value="" data-satuan="Satuan">-- Pilih Bahan Baku --</option>
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->id_bahan }}"
                                                data-satuan="{{ $bahan->satuan->singkatan ?? $bahan->satuan->nama_satuan ?? 'Unit' }}"
                                                {{ old('id_bahan', session('selected_bahan', $stokBahan->id_bahan)) == $bahan->id_bahan ? 'selected' : '' }}>
                                                {{ $bahan->nama_bahan }} ({{ $bahan->satuan->singkatan ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <a href="{{ route('bahan.create', ['redirect_to' => url()->current()]) }}" class="btn btn-outline-secondary font-weight-bold" title="Tambah Bahan Baru">...</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_supplier">Supplier <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="id_supplier" id="id_supplier" class="form-control" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id_supplier }}"
                                                {{ old('id_supplier', session('selected_supplier', $stokBahan->id_supplier)) == $supplier->id_supplier ? 'selected' : '' }}>
                                                {{ $supplier->nama_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <a href="{{ route('supplier.create', ['redirect_to' => url()->current()]) }}" class="btn btn-outline-secondary font-weight-bold" title="Tambah Supplier Baru">...</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Tanggal Beli, Tanggal Produksi & Tanggal Expired Berdampingan --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal_transaksi">Tanggal Beli <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="form-control"
                                    value="{{ old('tanggal_transaksi', $stokBahan->tanggal_transaksi) }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal_produksi">Tanggal Produksi <small class="text-muted">(Opsional)</small></label>
                                <input type="date" name="tanggal_produksi" id="tanggal_produksi" class="form-control"
                                    value="{{ old('tanggal_produksi', $stokBahan->tanggal_produksi) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal_expired">Tanggal Expired <small class="text-muted">(Opsional)</small></label>
                                <input type="date" name="tanggal_expired" id="tanggal_expired" class="form-control"
                                    value="{{ old('tanggal_expired', $stokBahan->tanggal_expired) }}">
                            </div>
                        </div>
                    </div>

                    {{-- 3. Jumlah (mengikuti satuan bahan), Harga Beli & Batas Notif --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jumlah">Jumlah Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="jumlah" id="jumlah" class="form-control"
                                        value="{{ old('jumlah', $stokBahan->jumlah) }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text font-weight-bold" id="label-satuan">Satuan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harga_beli">Harga Beli (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga_beli" id="harga_beli" class="form-control"
                                    value="{{ old('harga_beli', $stokBahan->harga_beli) }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="batas_notif_expired">Batas Notif Expired (Hari)</label>
                                <input type="number" name="batas_notif_expired" id="batas_notif_expired" class="form-control"
                                    value="{{ old('batas_notif_expired', $stokBahan->batas_notif_expired ?? 14) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
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