@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Master Supplier</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Master Supplier</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Master Data Supplier</h3>
        <div class="card-tools">
            <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Supplier
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
                        <th>Nama Supplier</th>
                        <th>Kontak / No. HP</th>
                        <th>Alamat</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $index => $supplier)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $supplier->nama_supplier }}</td>
                        <td>{{ $supplier->kontak ?? '-' }}</td>
                        <td>{{ $supplier->alamat ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('supplier.edit', $supplier->id_supplier) }}" class="btn btn-warning btn-sm">Edit</a>
                            @if($supplier->stok_bahans_count > 0)
                                <button type="button" class="btn btn-secondary btn-sm" disabled title="Supplier sudah memiliki riwayat transaksi">Terkunci</button>
                            @else
                                <form action="{{ route('supplier.destroy', $supplier->id_supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data supplier.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection