<?php

namespace App\Http\Controllers;

use App\Models\DetailProduksiModel;
use App\Models\ProdukModel;
use App\Models\ProduksiModel;
use App\Models\StokBahanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksis = ProduksiModel::with(['produk', 'user'])
            ->orderBy('tanggal_produksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('produksi.index', compact('produksis'));
    }

    public function create()
    {
        $produks = ProdukModel::with(['reseps.bahan.satuan', 'reseps.bahan.stokBahans'])
            ->orderBy('nama_produk', 'asc')
            ->get();

        $kodeOtomatis = 'PRD-' . date('Ymd') . '-' . str_pad(ProduksiModel::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);

        return view('produksi.create', compact('produks', 'kodeOtomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produksi'    => 'required|string|max:40|unique:produksis,kode_produksi',
            'id_produk'        => 'required|exists:produks,id_produk',
            'tanggal_produksi' => 'required|date',
            'jumlah_batch'     => 'required|numeric|gt:0',
            'catatan'          => 'nullable|string',
        ], [
            'kode_produksi.unique' => 'Kode produksi sudah digunakan.',
            'id_produk.required'   => 'Produk roti wajib dipilih.',
            'jumlah_batch.gt'      => 'Jumlah batch produksi harus lebih dari 0.',
        ]);

        $produk = ProdukModel::with('reseps.bahan.satuan')->findOrFail($request->id_produk);

        if ($produk->reseps->isEmpty()) {
            return back()->withInput()->with('error', 'Produk ini belum memiliki komposisi resep bahan baku.');
        }

        try {
            DB::transaction(function () use ($request, $produk) {
                $jumlahBatch = (float) $request->jumlah_batch;
                $totalUnitHasil = (int) round($jumlahBatch * $produk->porsi_per_batch);
                $totalBiayaOperasional = (int) round($jumlahBatch * $produk->biaya_operasional);
                $totalBiayaBahan = 0;

                $produksi = ProduksiModel::create([
                    'kode_produksi'           => strtoupper($request->kode_produksi),
                    'id_produk'               => $produk->id_produk,
                    'user_id'                 => Auth::id(),
                    'tanggal_produksi'        => $request->tanggal_produksi,
                    'jumlah_batch'            => $jumlahBatch,
                    'total_unit_hasil'        => max(1, $totalUnitHasil),
                    'total_biaya_bahan'       => 0,
                    'total_biaya_operasional' => $totalBiayaOperasional,
                    'total_hpp'               => 0,
                    'hpp_per_unit'            => 0,
                    'catatan'                 => $request->catatan,
                ]);

                foreach ($produk->reseps as $resep) {
                    $kebutuhan = round($resep->jumlah_takaran * $jumlahBatch, 2);

                    // Ambil batch stok yang masih punya sisa_stok > 0 dan belum expired melewati tanggal produksi,
                    // diurutkan berdasarkan FEFO (tanggal_expired paling cepat habis duluan, non-expired di akhir)
                    $batches = StokBahanModel::where('id_bahan', $resep->id_bahan)
                        ->where('sisa_stok', '>', 0)
                        ->where(function ($q) use ($request) {
                            $q->whereNull('tanggal_expired')
                              ->orWhere('tanggal_expired', '>=', $request->tanggal_produksi);
                        })
                        ->orderByRaw('CASE WHEN tanggal_expired IS NULL THEN 1 ELSE 0 END ASC')
                        ->orderBy('tanggal_expired', 'asc')
                        ->orderBy('tanggal_transaksi', 'asc')
                        ->lockForUpdate()
                        ->get();

                    $totalTersedia = $batches->sum('sisa_stok');
                    if ($totalTersedia < $kebutuhan) {
                        $namaBahan = $resep->bahan->nama_bahan ?? 'Bahan';
                        $satuan = $resep->bahan->satuan->singkatan ?? '';
                        throw new \Exception("Stok aktif (belum expired) untuk {$namaBahan} tidak mencukupi. Dibutuhkan: {$kebutuhan} {$satuan}, Tersedia: {$totalTersedia} {$satuan}.");
                    }

                    $sisaKebutuhan = $kebutuhan;

                    foreach ($batches as $batch) {
                        if ($sisaKebutuhan <= 0) {
                            break;
                        }

                        $ambil = min((float) $batch->sisa_stok, $sisaKebutuhan);
                        $hargaSatuan = $batch->jumlah > 0 ? ($batch->harga_beli / $batch->jumlah) : 0;
                        $subtotal = (int) round($ambil * $hargaSatuan);

                        DetailProduksiModel::create([
                            'id_produksi'        => $produksi->id_produksi,
                            'id_bahan'           => $resep->id_bahan,
                            'id_stok'            => $batch->id_stok,
                            'jumlah_terpakai'    => $ambil,
                            'harga_satuan_batch' => (int) round($hargaSatuan),
                            'subtotal_biaya'     => $subtotal,
                        ]);

                        $batch->decrement('sisa_stok', $ambil);

                        $totalBiayaBahan += $subtotal;
                        $sisaKebutuhan = round($sisaKebutuhan - $ambil, 2);
                    }
                }

                $totalHpp = $totalBiayaBahan + $totalBiayaOperasional;
                $hppPerUnit = $produksi->total_unit_hasil > 0
                    ? (int) round($totalHpp / $produksi->total_unit_hasil)
                    : 0;

                $produksi->update([
                    'total_biaya_bahan' => $totalBiayaBahan,
                    'total_hpp'         => $totalHpp,
                    'hpp_per_unit'      => $hppPerUnit,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('produksi.index')->with('success', 'Transaksi produksi berhasil disimpan, stok bahan terpotong otomatis (FEFO), dan HPP telah dihitung.');
    }

    public function show(ProduksiModel $produksi)
    {
        $produksi->load(['produk', 'user', 'details.bahan.satuan', 'details.stokBahan.supplier']);
        return view('produksi.show', compact('produksi'));
    }

    public function destroy(ProduksiModel $produksi)
    {
        DB::transaction(function () use ($produksi) {
            $produksi->load('details');

            // Kembalikan sisa stok ke masing-masing batch saat transaksi produksi dibatalkan/dihapus
            foreach ($produksi->details as $detail) {
                StokBahanModel::where('id_stok', $detail->id_stok)
                    ->increment('sisa_stok', $detail->jumlah_terpakai);
            }

            $produksi->delete();
        });

        return redirect()->route('produksi.index')->with('success', 'Transaksi produksi dibatalkan dan stok bahan telah dikembalikan ke gudang.');
    }
}