@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Master Bahan Baku</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Master Bahan Baku</li>
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
        <h3 class="card-title font-weight-bold">Master Data Bahan Baku</h3>
        @if(in_array($userRole, ['admin', 'gudang']))
        <div class="card-tools">
            <a href="{{ route('bahan.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Bahan
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

        <table id="tabelBahan" class="table table-bordered table-hover dt-responsive nowrap" style="width:100%">
            <thead class="thead-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Bahan</th>
                    <th>Satuan Beli</th>
                    <th style="width: 120px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bahans as $index => $bahan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $bahan->nama_bahan }}</td>
                    <td>
                        {{ $bahan->satuan->nama_satuan ?? '-' }}
                        <span class="badge badge-info">{{ $bahan->satuan->singkatan ?? '' }}</span>
                    </td>
                    <td class="text-center">
                        @if($userRole === 'admin')
                            @if($bahan->stok_bahans_count == 0)
                                <a href="{{ route('bahan.edit', $bahan->id_bahan) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('bahan.destroy', $bahan->id_bahan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bahan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="badge badge-secondary" title="Bahan sudah memiliki riwayat stok">
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
        $('#tabelBahan').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: "Belum ada data bahan baku."
            }
        });
    });
</script>
@endpush