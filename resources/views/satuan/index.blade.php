@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">Master Data Satuan</h5>
        <a href="{{ route('satuan.create') }}" class="btn btn-primary btn-sm">+ Tambah Satuan</a>
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
                        <th>Nama Satuan</th>
                        <th>Singkatan</th>
                        <th style="width: 170px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuans as $satuan)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $satuan->nama_satuan }}</td>
                            <td><span class="badge bg-secondary">{{ $satuan->singkatan }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('satuan.edit', $satuan->id_satuan) }}" class="btn btn-warning btn-sm">Edit</a>

                                @if($satuan->bahans_count == 0)
                                    <form action="{{ route('satuan.destroy', $satuan->id_satuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus satuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled title="Sedang digunakan di Master Bahan">Terkunci</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Belum ada data satuan. Silakan tambahkan (contoh: Kilogram, Liter, Bungkus).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection