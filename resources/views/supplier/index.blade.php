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
@php
    $userRole = auth()->user()->role ?? '';
@endphp

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Master Data Supplier</h3>
        @if(in_array($userRole, ['admin', 'gudang']))
        <div class="card-tools">
            <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Supplier
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table id="tabelSupplier" class="table table-bordered table-hover dt-responsive nowrap" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Supplier</th>
                    <th>Kontak / No. HP</th>
                    <th>Alamat</th>
                    @if($userRole === 'admin')
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $index => $supplier)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-weight-bold">{{ $supplier->nama_supplier }}</td>
                    <td>{{ $supplier->kontak ?? '-' }}</td>
                    <td>{{ $supplier->alamat ?? '-' }}</td>
                    @if($userRole === 'admin')
                    <td class="text-center">
                        @if($supplier->stok_bahans_count == 0)
                            <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('supplier.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted small">Digunakan</span>
                        @endif
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
        $('#tabelSupplier').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: "Belum ada data supplier."
            }
        });
    });
</script>
@endpush