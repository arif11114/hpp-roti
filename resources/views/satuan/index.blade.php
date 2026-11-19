@extends('layouts.app')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Master Data Satuan</h3>
        <div class="card-tools">
            <a href="{{ route('satuan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Satuan
            </a>
        </div>
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Satuan</th>
                        <th>Singkatan</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuans as $index => $satuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $satuan->nama_satuan }}</td>
                        <td><span class="badge badge-secondary">{{ $satuan->singkatan }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('satuan.edit', $satuan->id_satuan) }}" class="btn btn-warning btn-sm">Edit</a>
                            @if($satuan->bahans_count > 0)
                                <button type="button" class="btn btn-secondary btn-sm" disabled title="Satuan sedang digunakan di Master Bahan">Terkunci</button>
                            @else
                                <form action="{{ route('satuan.destroy', $satuan->id_satuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus satuan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data satuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection