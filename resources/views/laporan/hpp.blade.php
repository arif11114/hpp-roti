@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Laporan Perhitungan HPP</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Laporan HPP</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
{{-- Filter Periode & Tombol Cetak --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Filter Periode Laporan HPP Aktual</h3>
        <div class="card-tools">
            <button onclick="window.print()" class="btn btn-success btn-sm">
                <i class="fas fa-print mr-1"></i> Cetak Laporan
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.hpp') }}" method="GET" class="row align-items-end">
            <div class="col-md-4">
                <div class="form-group mb-md-0">
                    <label for="tanggal_mulai">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-md-0">
                    <label for="tanggal_selesai">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter mr-1"></i> Tampilkan Laporan
                </button>
                <a href="{{ route('laporan.hpp') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Info Boxes Ringkasan --}}
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-info"><i class="fas fa-industry"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Produksi</span>
                <span class="info-box-number">{{ $ringkasan['total_transaksi'] }}x ({{ $ringkasan['total_unit'] }} pcs)</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Biaya Bahan</span>
                <span class="info-box-number">Rp {{ number_format($ringkasan['total_biaya_bahan'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-secondary"><i class="fas fa-bolt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Biaya Operasional</span>
                <span class="info-box-number">Rp {{ number_format($ringkasan['total_operasional'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="info-box shadow-sm">
            <span class="info-box-icon bg-success"><i class="fas fa-calculator"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Akumulasi Total HPP</span>
                <span class="info-box-number">Rp {{ number_format($ringkasan['total_hpp'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Tabel 1: Laporan HPP Aktual per Transaksi Produksi --}}
<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">1. Laporan HPP Aktual Berdasarkan Transaksi Produksi ({{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }})</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Tanggal</th>
                    <th>Kode Produksi</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Hasil (Pcs)</th>
                    <th class="text-right">Biaya Bahan</th>
                    <th class="text-right">Biaya Operasional</th>
                    <th class="text-right">Total HPP</th>
                    <th class="text-right">HPP / Pcs</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksis as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_produksi)->format('d-m-Y') }}</td>
                    <td><a href="{{ route('produksi.show', $p) }}" class="badge badge-secondary">{{ $p->kode_produksi }}</a></td>
                    <td class="font-weight-bold">{{ $p->produk->nama_produk ?? '-' }}</td>
                    <td class="text-center">{{ $p->total_unit_hasil }} pcs</td>
                    <td class="text-right">Rp {{ number_format($p->total_biaya_bahan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->total_biaya_operasional, 0, ',', '.') }}</td>
                    <td class="text-right font-weight-bold">Rp {{ number_format($p->total_hpp, 0, ',', '.') }}</td>
                    <td class="text-right font-weight-bold text-success">Rp {{ number_format($p->hpp_per_unit, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Tidak ada transaksi produksi pada rentang tanggal ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tabel 2: Analisis HPP Standar & Margin Profit per Produk --}}
<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">2. Analisis HPP Standar & Margin Keuntungan per Produk Roti</h3>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Porsi / Batch</th>
                    <th class="text-right">HPP / Batch</th>
                    <th class="text-right">HPP / Pcs</th>
                    <th class="text-right">Harga Jual / Pcs</th>
                    <th class="text-right">Laba Kotor / Pcs</th>
                </tr>
            </thead>
            <tbody>
                @forelse($analisisProduk as $index => $ap)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><span class="badge badge-secondary">{{ $ap->kode_produk }}</span></td>
                    <td class="font-weight-bold">{{ $ap->nama_produk }}</td>
                    <td class="text-center">{{ $ap->porsi_per_batch }} pcs</td>
                    <td class="text-right">Rp {{ number_format($ap->total_hpp_batch, 0, ',', '.') }}</td>
                    <td class="text-right font-weight-bold text-primary">Rp {{ number_format($ap->hpp_unit, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($ap->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($ap->harga_jual <= 0)
                            <span class="badge badge-secondary">Harga jual belum diatur</span>
                        @elseif($ap->margin_unit >= 0)
                            <span class="text-success font-weight-bold">
                                Rp {{ number_format($ap->margin_unit, 0, ',', '.') }} ({{ $ap->persen_margin }}%)
                            </span>
                        @else
                            <span class="text-danger font-weight-bold">
                                Rugi Rp {{ number_format(abs($ap->margin_unit), 0, ',', '.') }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Belum ada data produk roti.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection