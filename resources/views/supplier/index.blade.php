@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0 fw-bold">Data Master Supplier</h3>
    <a href="{{ route('supplier.create') }}" class="btn btn-primary">+ Tambah Supplier</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-primary text-center">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Supplier</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th width="140">Riwayat Stok</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $supplier->nama_supplier }}</td>
                        <td>{{ $supplier->kontak ?? '-' }}</td>
                        <td>{{ $supplier->alamat ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $supplier->stok_bahans_count }} Transaksi</span>
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('supplier.edit', $supplier->id_supplier) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('supplier.destroy', $supplier->id_supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" {{ $supplier->stok_bahans_count > 0 ? 'disabled' : '' }}>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data supplier. Silakan tambah supplier baru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection