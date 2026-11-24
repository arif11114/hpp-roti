@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Rincian HPP Produksi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produksi.index') }}">Transaksi Produksi</a></li>
            <li class="breadcrumb-item active">{{ $produksi->kode_produksi }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Rincian Pemakaian Bahan & Perhitungan HPP ({{ $produksi->kode_produksi }})</h3>
        <div class="card-tools">
            <button onclick="window.print()" class="btn btn-default btn-sm mr-1">
                <i class="fas fa-print mr-1"></i> Cetak
            </button>
            <a href="{{ route('produksi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <small class="text-muted">Produk Roti</small>
                <h6 class="font-weight-bold">{{ $produksi->produk->nama_produk ?? '-' }}</h6>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Tanggal & Jumlah Batch</small>
                <h6 class="font-weight-bold">
                    {{ \Carbon\Carbon::parse($produksi->tanggal_produksi)->format('d-m-Y') }}
                    ({{ number_format($produksi->jumlah_batch, 1, ',', '.') }} batch / {{ $produksi->total_unit_hasil }} pcs)
                </h6>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Total HPP Produksi</small>
                <h6 class="font-weight-bold text-primary">Rp {{ number_format($produksi->total_hpp, 0, ',', '.') }}</h6>
            </div>
            <div class="col-md-3">
                <small class="text-muted">HPP Per Unit Roti (Pcs)</small>
                <h5 class="font-weight-bold text-success mb-0">Rp {{ number_format($produksi->hpp_per_unit, 0, ',', '.') }} / pcs</h5>
            </div>
        </div>

        <h6 class="font-weight-bold">Rincian Pemotongan Batch Stok Bahan Baku (FEFO)</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th>Nama Bahan</th>
                        <th>Batch Supplier & Tgl Expired</th>
                        <th class="text-right">Jumlah Terpakai</th>
                        <th class="text-right">Harga / Satuan</th>
                        <th class="text-right">Subtotal Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produksi->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $detail->bahan->nama_bahan ?? '-' }}</td>
                        <td>
                            {{ $detail->stokBahan->supplier->nama_supplier ?? '-' }}
                            <small class="text-muted d-block">
                                Tgl Beli: {{ $detail->stokBahan ? \Carbon\Carbon::parse($detail->stokBahan->tanggal_transaksi)->format('d-m-Y') : '-' }}
                                | Exp: {{ $detail->stokBahan && $detail->stokBahan->tanggal_expired ? \Carbon\Carbon::parse($detail->stokBahan->tanggal_expired)->format('d-m-Y') : 'Non-Expired' }}
                            </small>
                        </td>
                        <td class="text-right">
                            {{ number_format($detail->jumlah_terpakai, 2, ',', '.') }}
                            <span class="badge badge-info">{{ $detail->bahan->satuan->singkatan ?? '' }}</span>
                        </td>
                        <td class="text-right">Rp {{ number_format($detail->harga_satuan_batch, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal_biaya, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total Biaya Bahan Baku</th>
                        <th class="text-right">Rp {{ number_format($produksi->total_biaya_bahan, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Total Biaya Operasional ({{ number_format($produksi->jumlah_batch, 1, ',', '.') }} batch)</th>
                        <th class="text-right">Rp {{ number_format($produksi->total_biaya_operasional, 0, ',', '.') }}</th>
                    </tr>
                    <tr class="table-primary">
                        <th colspan="5" class="text-right">TOTAL HARGA POKOK PRODUKSI (HPP)</th>
                        <th class="text-right">Rp {{ number_format($produksi->total_hpp, 0, ',', '.') }}</th>
                    </tr>
                    <tr class="table-success">
                        <th colspan="5" class="text-right">HPP PER UNIT ROTI ({{ $produksi->total_unit_hasil }} pcs)</th>
                        <th class="text-right">Rp {{ number_format($produksi->hpp_per_unit, 0, ',', '.') }} / pcs</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection