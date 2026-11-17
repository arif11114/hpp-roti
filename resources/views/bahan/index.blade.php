@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">Master Data Bahan Baku</h5>
        <a href="{{ route('bahan.create') }}" class="btn btn-primary btn-sm">+ Tambah Bahan</a>
    </div>
    <div class="card-body">
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

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Nama Bahan</th>
                        <th>Satuan Beli</th>
                        <th style="width: 170px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahans as $bahan)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $bahan->nama_bahan }}</td>
                            <td>
                                {{ $bahan->satuan->nama_satuan }}
                                <span class="badge bg-info text-dark">{{ $bahan->satuan->singkatan }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('bahan.edit', $bahan->id_bahan) }}" class="btn btn-warning btn-sm">Edit</a>

                                @if($bahan->stok_bahans_count == 0)
                                    <form action="{{ route('bahan.destroy', $bahan->id_bahan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bahan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled title="Bahan sudah terpakai di transaksi stok">Terkunci</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada data bahan baku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection