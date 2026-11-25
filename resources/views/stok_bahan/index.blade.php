@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Stok Bahan Baku</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Stok Bahan Baku</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@php
    $userRole = auth()->user()->role ?? '';
@endphp

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Data Transaksi Stok Bahan Baku</h3>
        @if(in_array($userRole, ['admin', 'gudang']))
        <div class="card-tools">
            <a href="{{ route('stok-bahan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Stok Bahan
            </a>
        </div>
        @endif
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
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('warning') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table id="tabelStokBahan" class="table table-bordered table-hover dt-responsive nowrap" style="width:100%">
            <thead class="thead-light text-center">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Bahan</th>
                    <th>Supplier</th>
                    <th>Tgl Beli</th>
                    <th>Tgl Produksi</th>
                    <th>Tgl Expired</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Status Expired</th>
                    <th>Bukti Nota</th>
                    @if($userRole === 'admin')
                        <th style="width: 100px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($stokBahans as $index => $stok)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $stok->bahan->nama_bahan ?? '-' }}</td>
                        <td>{{ $stok->supplier->nama_supplier ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($stok->tanggal_transaksi)->format('d-m-Y') }}</td>
                        <td class="text-center">
                            {{ $stok->tanggal_produksi ? \Carbon\Carbon::parse($stok->tanggal_produksi)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $stok->tanggal_expired ? \Carbon\Carbon::parse($stok->tanggal_expired)->format('d-m-Y') : 'Tanpa Expired' }}
                        </td>
                        <td class="text-right">
                            {{ number_format($stok->jumlah, 2, ',', '.') }}
                            <span class="badge badge-info">{{ $stok->bahan->satuan->singkatan ?? '' }}</span>
                        </td>
                        <td class="text-right">Rp {{ number_format($stok->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if(!$stok->tanggal_expired)
                                <span class="badge badge-secondary">Non-Expired</span>
                            @else
                                @php
                                    $sisaHari = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($stok->tanggal_expired)->startOfDay(), false);
                                @endphp
                                @if($sisaHari < 0)
                                    <span class="badge badge-danger">Expired ({{ abs($sisaHari) }} hari lalu)</span>
                                @elseif($sisaHari <= $stok->batas_notif_expired)
                                    <span class="badge badge-warning">Hampir Expired ({{ $sisaHari }} hari lagi)</span>
                                @else
                                    <span class="badge badge-success">Aman ({{ $sisaHari }} hari lagi)</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stok->bukti_nota)
                                <a href="{{ asset('storage/' . $stok->bukti_nota) }}" target="_blank" class="btn btn-info btn-xs">
                                    <i class="fas fa-file-invoice mr-1"></i> Lihat Nota
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        @if($userRole === 'admin')
                        <td class="text-center text-nowrap">
                            <a href="{{ route('stok-bahan.edit', $stok) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('stok-bahan.destroy', $stok) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#tabelStokBahan').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: "Belum ada data stok bahan. Silakan tambah stok baru."
            }
        });
    });
</script>
@endpush