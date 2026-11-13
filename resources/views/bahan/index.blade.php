@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-primary">Daftar Master Bahan</h5>
        <a href="{{ route('bahan.create') }}" class="btn btn-primary btn-sm">
            + Tambah Bahan Baru
        </a>
    </div>

    <div class="card-body">
        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tabel Data Bahan -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="80" class="text-center">ID</th>
                        <th>Nama Bahan</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahans as $bahan)
                        <tr>
                            <td class="text-center">{{ $bahan->id_bahan }}</td>
                            <td class="fw-semibold">{{ $bahan->nama_bahan }}</td>
                            <td class="text-center">
                                <a href="{{ route('bahan.edit', $bahan->id_bahan) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('bahan.destroy', $bahan->id_bahan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bahan {{ $bahan->nama_bahan }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada data bahan. Silakan klik tombol <strong>+ Tambah Bahan Baru</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection