@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0 fw-bold">Data Stok Bahan Baku</h3>
    <a href="{{ route('stok-bahan.create') }}" class="btn btn-primary">+ Tambah Stok Bahan</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Supplier</th>
                    <th>Tgl Beli</th>
                    <th>Tgl Produksi</th>
                    <th>Tgl Expired</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Status Expired</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stokBahans as $index => $stok)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $stok->bahan->nama_bahan ?? '-' }}</td>
                        <td>{{ $stok->supplier->nama_supplier ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($stok->tanggal_transaksi)->format('d-m-Y') }}</td>
                        <td class="text-center">
                            {{ $stok->tanggal_produksi ? \Carbon\Carbon::parse($stok->tanggal_produksi)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $stok->tanggal_expired ? \Carbon\Carbon::parse($stok->tanggal_expired)->format('d-m-Y') : 'Tanpa Expired' }}
                        </td>
                        <td class="text-end">
                            {{ number_format($stok->jumlah, 2, ',', '.') }}
                            <span class="badge bg-secondary">{{ $stok->bahan->satuan->singkatan ?? '' }}</span>
                        </td>
                        <td class="text-end">Rp {{ number_format($stok->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if(!$stok->tanggal_expired)
                                <span class="badge bg-info text-dark">Non-Expired</span>
                            @else
                                @php
                                    $sisaHari = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($stok->tanggal_expired)->startOfDay(), false);
                                @endphp
                                @if($sisaHari < 0)
                                    <span class="badge bg-danger">Expired ({{ abs($sisaHari) }} hari lalu)</span>
                                @elseif($sisaHari <= $stok->batas_notif_expired)
                                    <span class="badge bg-warning text-dark">Hampir Expired ({{ $sisaHari }} hari lagi)</span>
                                @else
                                    <span class="badge bg-success">Aman ({{ $sisaHari }} hari lagi)</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('stok-bahan.edit', $stok->id_stok) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('stok-bahan.destroy', $stok->id_stok) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada data stok bahan. Silakan tambah stok baru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection