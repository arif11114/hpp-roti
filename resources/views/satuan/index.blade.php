@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Master Satuan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Master Satuan</li>
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
        <h3 class="card-title font-weight-bold">Master Data Satuan</h3>
        @if(in_array($userRole, ['admin', 'gudang']))
        <div class="card-tools">
            <a href="{{ route('satuan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Satuan
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

        <table id="tabelSatuan" class="table table-bordered table-hover dt-responsive nowrap" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Satuan</th>
                    <th>Singkatan</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($satuans as $index => $satuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $satuan->nama_satuan }}</td>
                    <td><span class="badge badge-secondary">{{ $satuan->singkatan }}</span></td>
                    <td class="text-center">
                        @if($userRole === 'admin')
                            @if($satuan->bahans_count == 0)
                                <a href="{{ route('satuan.edit', $satuan->id_satuan) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('satuan.destroy', $satuan->id_satuan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus satuan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge badge-secondary" title="Satuan sedang digunakan di Master Bahan">
                                    <i class="fas fa-lock mr-1"></i> Digunakan
                                </span>
                            @endif
                        @else
                            <span class="badge badge-light border text-muted">
                                <i class="fas fa-eye mr-1"></i> Lihat Saja
                            </span>
                        @endif
                    </td>
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
        $('#tabelSatuan').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: "Belum ada data satuan."
            }
        });
    });
</script>
@endpush