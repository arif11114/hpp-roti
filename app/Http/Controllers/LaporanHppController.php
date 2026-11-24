<?php

namespace App\Http\Controllers;

use App\Models\ProdukModel;
use App\Models\ProduksiModel;
use Illuminate\Http\Request;

class LaporanHppController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->query('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->query('tanggal_selesai', now()->format('Y-m-d'));

        // 1. Rekap HPP Aktual dari Transaksi Produksi
        $produksis = ProduksiModel::with(['produk', 'user'])
            ->whereBetween('tanggal_produksi', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal_produksi', 'desc')
            ->get();

        $ringkasan = [
            'total_transaksi'   => $produksis->count(),
            'total_unit'        => $produksis->sum('total_unit_hasil'),
            'total_biaya_bahan' => $produksis->sum('total_biaya_bahan'),
            'total_operasional' => $produksis->sum('total_biaya_operasional'),
            'total_hpp'         => $produksis->sum('total_hpp'),
        ];

        // 2. Analisis HPP Standar per Produk (Berdasarkan Harga Stok Gudang)
        $produks = ProdukModel::with(['reseps.bahan.stokBahans'])->orderBy('nama_produk', 'asc')->get();

        $analisisProduk = $produks->map(function ($produk) {
            $biayaBahanStandar = 0;

            foreach ($produk->reseps as $resep) {
                $bahan = $resep->bahan;
                if ($bahan && $bahan->stokBahans->count() > 0) {
                    $totalJumlah = $bahan->stokBahans->sum('jumlah');
                    $totalHarga = $bahan->stokBahans->sum('harga_beli');
                    $rataHargaSatuan = $totalJumlah > 0 ? ($totalHarga / $totalJumlah) : 0;
                    $biayaBahanStandar += ($resep->jumlah_takaran * $rataHargaSatuan);
                }
            }

            $totalHppBatch = round($biayaBahanStandar + $produk->biaya_operasional);
            $hppUnit = $produk->porsi_per_batch > 0 ? round($totalHppBatch / $produk->porsi_per_batch) : 0;
            $marginUnit = $produk->harga_jual - $hppUnit;
            $persenMargin = $produk->harga_jual > 0 ? round(($marginUnit / $produk->harga_jual) * 100, 1) : 0;

            return (object) [
                'kode_produk'       => $produk->kode_produk,
                'nama_produk'       => $produk->nama_produk,
                'porsi_per_batch'   => $produk->porsi_per_batch,
                'biaya_bahan'       => round($biayaBahanStandar),
                'biaya_operasional' => $produk->biaya_operasional,
                'total_hpp_batch'   => $totalHppBatch,
                'hpp_unit'          => $hppUnit,
                'harga_jual'        => $produk->harga_jual,
                'margin_unit'       => $marginUnit,
                'persen_margin'     => $persenMargin,
            ];
        });

        return view('laporan.hpp', compact(
            'produksis',
            'ringkasan',
            'analisisProduk',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }
}