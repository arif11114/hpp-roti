@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Edit Produk & Resep</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Master Produk</a></li>
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
                <h5 class="card-title font-weight-bold mb-0">Edit Produk: {{ $produk->nama_produk }}</h5>
            </div>
            <div class="card-body">
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

                <form action="{{ route('produk.update', $produk) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kode_produk">Kode Produk <span class="text-danger">*</span></label>
                                <input type="text" name="kode_produk" id="kode_produk" class="form-control"
                                    value="{{ old('kode_produk', $produk->kode_produk) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_produk">Nama Produk Roti <span class="text-danger">*</span></label>
                                <input type="text" name="nama_produk" id="nama_produk" class="form-control"
                                    value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="porsi_per_batch">Hasil per 1 Batch (Pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="porsi_per_batch" id="porsi_per_batch" class="form-control"
                                    value="{{ old('porsi_per_batch', $produk->porsi_per_batch) }}" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="biaya_operasional">Biaya Operasional / Batch (Rp)</label>
                                <input type="number" name="biaya_operasional" id="biaya_operasional" class="form-control"
                                    value="{{ old('biaya_operasional', $produk->biaya_operasional) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harga_jual">Estimasi Harga Jual / Pcs (Rp)</label>
                                <input type="number" name="harga_jual" id="harga_jual" class="form-control"
                                    value="{{ old('harga_jual', $produk->harga_jual) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="keterangan">Keterangan <small class="text-muted">(Opsional)</small></label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control"
                                    value="{{ old('keterangan', $produk->keterangan) }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="font-weight-bold mb-0"><i class="fas fa-mortar-pestle mr-1"></i> Komposisi Bahan Baku (Per 1 Batch Resep)</h6>
                        <button type="button" id="btn-tambah-bahan" class="btn btn-success btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Baris Bahan
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-resep">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Bahan Baku <span class="text-danger">*</span></th>
                                    <th style="width: 240px;">Takaran per Batch <span class="text-danger">*</span></th>
                                    <th style="width: 80px;" class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="resep-container">
                                @foreach($produk->reseps as $resep)
                                <tr>
                                    <td>
                                        <select name="id_bahan[]" class="form-control select-bahan" required>
                                            <option value="" data-satuan="Satuan">-- Pilih Bahan Baku --</option>
                                            @foreach($bahans as $bahan)
                                                <option value="{{ $bahan->id_bahan }}"
                                                    data-satuan="{{ $bahan->satuan->singkatan ?? '-' }}"
                                                    {{ $resep->id_bahan == $bahan->id_bahan ? 'selected' : '' }}>
                                                    {{ $bahan->nama_bahan }} ({{ $bahan->satuan->singkatan ?? '-' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="jumlah_takaran[]" class="form-control"
                                                value="{{ $resep->jumlah_takaran }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold label-satuan">{{ $resep->bahan->satuan->singkatan ?? 'Satuan' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus-baris" {{ $produk->reseps->count() <= 1 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Produk & Resep</button>
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
        function updateRemoveButtons() {
            const rowCount = $('#resep-container tr').length;
            $('.btn-hapus-baris').prop('disabled', rowCount <= 1);
        }

        $(document).on('change', '.select-bahan', function () {
            const satuan = $(this).find(':selected').data('satuan') || 'Satuan';
            $(this).closest('tr').find('.label-satuan').text(satuan);
        });

        $('#btn-tambah-bahan').click(function () {
            const firstRow = $('#resep-container tr:first').clone();
            firstRow.find('select').val('');
            firstRow.find('input').val('');
            firstRow.find('.label-satuan').text('Satuan');
            $('#resep-container').append(firstRow);
            updateRemoveButtons();
        });

        $(document).on('click', '.btn-hapus-baris', function () {
            if ($('#resep-container tr').length > 1) {
                $(this).closest('tr').remove();
                updateRemoveButtons();
            }
        });
    });
</script>
@endpush