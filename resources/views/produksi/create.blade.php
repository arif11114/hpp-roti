@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Input Produksi Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produksi.index') }}">Transaksi Produksi</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title font-weight-bold mb-0">Form Transaksi Produksi (Otomatis Potong Stok FEFO)</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
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

                <form action="{{ route('produksi.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kode_produksi">Kode Produksi <span class="text-danger">*</span></label>
                                <input type="text" name="kode_produksi" id="kode_produksi" class="form-control"
                                    value="{{ old('kode_produksi', $kodeOtomatis) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal_produksi">Tanggal Produksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_produksi" id="tanggal_produksi" class="form-control"
                                    value="{{ old('tanggal_produksi', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jumlah_batch">Jumlah Batch Produksi <span class="text-danger">*</span></label>
                                <input type="number" step="0.5" min="0.5" name="jumlah_batch" id="jumlah_batch" class="form-control"
                                    value="{{ old('jumlah_batch', 1) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="id_produk">Pilih Produk Roti <span class="text-danger">*</span></label>
                                <select name="id_produk" id="id_produk" class="form-control" required>
                                    <option value="">-- Pilih Produk yang Akan Diproduksi --</option>
                                    @foreach($produks as $produk)
                                        @php
                                            $resepData = $produk->reseps->map(function($r) {
                                                return [
                                                    'nama'     => $r->bahan->nama_bahan ?? '-',
                                                    'takaran'  => (float) $r->jumlah_takaran,
                                                    'satuan'   => $r->bahan->satuan->singkatan ?? '',
                                                    'tersedia' => (float) ($r->bahan->total_stok_tersedia ?? 0),
                                                ];
                                            });
                                        @endphp
                                        <option value="{{ $produk->id_produk }}"
                                            data-porsi="{{ $produk->porsi_per_batch }}"
                                            data-operasional="{{ $produk->biaya_operasional }}"
                                            data-reseps='@json($resepData)'
                                            {{ old('id_produk') == $produk->id_produk ? 'selected' : '' }}>
                                            {{ $produk->kode_produk }} - {{ $produk->nama_produk }} ({{ $produk->porsi_per_batch }} pcs / batch)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estimasi Hasil Roti</label>
                                <input type="text" id="estimasi-hasil" class="form-control font-weight-bold bg-light" readonly value="0 pcs">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan Produksi <small class="text-muted">(Opsional)</small></label>
                        <input type="text" name="catatan" id="catatan" class="form-control"
                            value="{{ old('catatan') }}" placeholder="Contoh: Produksi shift pagi">
                    </div>

                    {{-- Simulasi Kebutuhan Bahan Baku --}}
                    <div id="preview-kebutuhan" class="mt-3" style="display: none;">
                        <h6 class="font-weight-bold"><i class="fas fa-clipboard-check mr-1"></i> Simulasi Kebutuhan Bahan Baku & Stok Gudang</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Bahan Baku</th>
                                        <th class="text-right">Total Dibutuhkan</th>
                                        <th class="text-right">Total Sisa Stok Gudang</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-kebutuhan"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('produksi.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Proses Produksi & Hitung HPP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        function hitungSimulasi() {
            const selected = $('#id_produk').find(':selected');
            const batch = parseFloat($('#jumlah_batch').val()) || 0;
            const porsi = parseInt(selected.data('porsi')) || 0;
            const reseps = selected.data('reseps');

            $('#estimasi-hasil').val(Math.round(batch * porsi) + ' pcs');

            if (reseps && reseps.length > 0 && batch > 0) {
                let html = '';
                reseps.forEach(function (item) {
                    const butuh = (item.takaran * batch).toFixed(2);
                    const cukup = item.tersedia >= parseFloat(butuh);
                    const badge = cukup
                        ? '<span class="badge badge-success">Cukup</span>'
                        : '<span class="badge badge-danger">Stok Kurang</span>';

                    html += `<tr>
                        <td class="font-weight-bold">${item.nama}</td>
                        <td class="text-right">${butuh} ${item.satuan}</td>
                        <td class="text-right">${item.tersedia.toFixed(2)} ${item.satuan}</td>
                        <td class="text-center">${badge}</td>
                    </tr>`;
                });
                $('#tbody-kebutuhan').html(html);
                $('#preview-kebutuhan').show();
            } else {
                $('#preview-kebutuhan').hide();
            }
        }

        $('#id_produk, #jumlah_batch').on('change keyup', hitungSimulasi);
        hitungSimulasi();
    });
</script>
@endpush