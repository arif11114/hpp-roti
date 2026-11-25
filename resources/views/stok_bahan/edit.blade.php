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

                <form action="{{ route('stok-bahan.update', $stokBahan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- 1. Nama Bahan & Supplier + Tombol Modal AJAX (...) --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_bahan">Nama Bahan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="id_bahan" id="id_bahan" class="form-control" required>
                                        <option value="" data-satuan="Satuan" data-kadaluarsa="30">-- Pilih Bahan Baku --</option>
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->id_bahan }}"
                                                data-satuan="{{ $bahan->satuan->singkatan ?? $bahan->satuan->nama_satuan ?? 'Unit' }}"
                                                data-kadaluarsa="{{ $bahan->masa_kadaluarsa_hari ?? 30 }}"
                                                {{ old('id_bahan', session('selected_bahan', $stokBahan->id_bahan)) == $bahan->id_bahan ? 'selected' : '' }}>
                                                {{ $bahan->nama_bahan }} ({{ $bahan->satuan->singkatan ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary font-weight-bold" data-toggle="modal" data-target="#modalTambahBahan" title="Tambah Bahan Baru">...</button>
                                    </div>
                                </div>
                                <small class="form-text text-muted" id="info-kadaluarsa-normatif">Acuan kadaluarsa normatif bahan.</small>
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
                                        <button type="button" class="btn btn-outline-secondary font-weight-bold" data-toggle="modal" data-target="#modalTambahSupplier" title="Tambah Supplier Baru">...</button>
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
                                    value="{{ old('tanggal_transaksi', $stokBahan->tanggal_transaksi) }}" max="{{ date('Y-m-d') }}" required>
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
                                <label for="tanggal_expired">Tanggal Expired <small class="text-muted">(Min. H+2)</small></label>
                                <input type="date" name="tanggal_expired" id="tanggal_expired" class="form-control"
                                    value="{{ old('tanggal_expired', $stokBahan->tanggal_expired) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Alert Peringatan Real-Time jika Expired Lebih Cepat dari Standar Normatif --}}
                    <div id="warning-expired-mepet" class="alert alert-warning d-none py-2 px-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span id="text-warning-expired"></span>
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

                    {{-- 4. Upload Bukti Nota --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="bukti_nota">Bukti Nota / Struk Pembelian <small class="text-muted">(Kosongkan jika tidak ingin mengganti - JPG, PNG, PDF maks. 2MB)</small></label>
                                @if($stokBahan->bukti_nota)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $stokBahan->bukti_nota) }}" target="_blank" class="btn btn-info btn-xs">
                                            <i class="fas fa-file-invoice mr-1"></i> Lihat Nota Saat Ini
                                        </a>
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" name="bukti_nota" id="bukti_nota" class="custom-file-input" accept=".jpg,.jpeg,.png,.pdf">
                                    <label class="custom-file-label" for="bukti_nota">Pilih file nota baru...</label>
                                </div>
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

@include('layouts.partials.quick_modals')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectBahan = document.getElementById('id_bahan');
        const labelSatuan = document.getElementById('label-satuan');
        const infoNormatif = document.getElementById('info-kadaluarsa-normatif');
        const inputTglBeli = document.getElementById('tanggal_transaksi');
        const inputTglProduksi = document.getElementById('tanggal_produksi');
        const inputTglExpired = document.getElementById('tanggal_expired');
        const boxWarning = document.getElementById('warning-expired-mepet');
        const textWarning = document.getElementById('text-warning-expired');
        const inputBuktiNota = document.getElementById('bukti_nota');

        function addDaysToDate(dateStr, days) {
            const parts = dateStr.split('-');
            const dt = new Date(parts[0], parts[1] - 1, parts[2]);
            dt.setDate(dt.getDate() + parseInt(days, 10));
            const yyyy = dt.getFullYear();
            const mm = String(dt.getMonth() + 1).padStart(2, '0');
            const dd = String(dt.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        function diffDaysBetween(startStr, endStr) {
            const p1 = startStr.split('-');
            const p2 = endStr.split('-');
            const d1 = new Date(p1[0], p1[1] - 1, p1[2]);
            const d2 = new Date(p2[0], p2[1] - 1, p2[2]);
            return Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
        }

        function checkWarningExpired() {
            const selectedOption = selectBahan.options[selectBahan.selectedIndex];
            const masaNormatif = parseInt(selectedOption.getAttribute('data-kadaluarsa') || '0', 10);
            const tglBeli = inputTglBeli.value;
            const tglExp = inputTglExpired.value;

            if (selectBahan.value && masaNormatif > 0 && tglBeli && tglExp) {
                const selisih = diffDaysBetween(tglBeli, tglExp);
                if (selisih < masaNormatif) {
                    textWarning.textContent = `Perhatian: Batas kadaluarsa normatif bahan ini adalah ${masaNormatif} hari, tetapi tanggal expired yang dipilih hanya berselisih ${selisih} hari dari tanggal beli.`;
                    boxWarning.classList.remove('d-none');
                    return;
                }
            }
            boxWarning.classList.add('d-none');
        }

        function updateInfoBahan() {
            const selectedOption = selectBahan.options[selectBahan.selectedIndex];
            labelSatuan.textContent = selectedOption.getAttribute('data-satuan') || 'Satuan';

            const masaNormatif = parseInt(selectedOption.getAttribute('data-kadaluarsa') || '30', 10);
            if (selectBahan.value) {
                infoNormatif.innerHTML = `Acuan kadaluarsa normatif bahan ini: <strong>${masaNormatif} hari</strong> dari tanggal beli.`;
            }
            checkWarningExpired();
        }

        function syncTanggal() {
            const tglBeli = inputTglBeli.value;
            if (tglBeli) {
                inputTglExpired.min = addDaysToDate(tglBeli, 2);
                inputTglProduksi.max = tglBeli;
            }
            checkWarningExpired();
        }

        if (inputBuktiNota) {
            inputBuktiNota.addEventListener('change', function (e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file nota baru...';
                e.target.nextElementSibling.textContent = fileName;
            });
        }

        selectBahan.addEventListener('change', updateInfoBahan);
        inputTglBeli.addEventListener('change', syncTanggal);
        inputTglExpired.addEventListener('change', checkWarningExpired);

        updateInfoBahan();
        syncTanggal();
    });
</script>
@endsection