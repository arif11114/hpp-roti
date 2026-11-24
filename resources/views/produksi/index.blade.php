@extends('layouts.app')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 font-weight-bold text-dark">Transaksi Produksi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Transaksi Produksi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">Riwayat Produksi Roti & Pemotongan Stok FEFO</h3>
        @if(in_array(auth()->user()->role, ['admin', 'produksi']))
        <div class="card-tools">
            <a href="{{ route('produksi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Input Produksi Baru
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

        <div class="table-responsive">
            <table id="table-produksi" class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 45px;">No</th>
                        <th>Kode Produksi</th>
                        <th>Tanggal</th>
                        <th>Produk Roti</th>
                        <th class="text-center">Batch / Hasil</th>
                        <th class="text-right">Total HPP</th>
                        <th class="text-right">HPP / Pcs</th>
                        <th>PIC</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produksis as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge badge-secondary">{{ $item->kode_produksi }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_produksi)->format('d-m-Y') }}</td>
                        <td class="font-weight-bold">{{ $item->produk->nama_produk ?? '-' }}</td>
                        <td class="text-center">
                            {{ number_format($item->jumlah_batch, 1, ',', '.') }} batch
                            <span class="badge badge-info d-block">{{ $item->total_unit_hasil }} pcs</span>
                        </td>
                        <td class="text-right">Rp {{ number_format($item->total_hpp, 0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold text-primary">Rp {{ number_format($item->hpp_per_unit, 0, ',', '.') }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('produksi.show', $item) }}" class="btn btn-info btn-sm">Rincian HPP</a>
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('produksi.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan transaksi produksi ini dan kembalikan stok ke gudang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Batal</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada transaksi produksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#table-produksi').DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@endpush