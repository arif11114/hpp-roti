<?php

namespace App\Http\Controllers;

use App\Models\BahanModel;
use App\Models\ProdukModel;
use App\Models\ResepProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = ProdukModel::with(['reseps.bahan.satuan'])
            ->withCount('produksis')
            ->orderBy('nama_produk', 'asc')
            ->get();

        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        $bahans = BahanModel::with('satuan')->orderBy('nama_bahan', 'asc')->get();

        // Generate kode produk otomatis (misal: PRD-001)
        $lastCount = ProdukModel::count() + 1;
        $kodeOtomatis = 'PRD-' . str_pad($lastCount, 3, '0', STR_PAD_LEFT);

        return view('produk.create', compact('bahans', 'kodeOtomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk'        => 'required|string|max:30|unique:produks,kode_produk',
            'nama_produk'        => 'required|string|max:100|unique:produks,nama_produk',
            'porsi_per_batch'    => 'required|integer|min:1',
            'biaya_operasional'  => 'nullable|numeric|min:0',
            'harga_jual'         => 'nullable|numeric|min:0',
            'keterangan'         => 'nullable|string',
            'id_bahan'           => 'required|array|min:1',
            'id_bahan.*'         => 'required|exists:bahans,id_bahan|distinct',
            'jumlah_takaran'     => 'required|array|min:1',
            'jumlah_takaran.*'   => 'required|numeric|gt:0',
        ], [
            'kode_produk.unique'       => 'Kode produk sudah digunakan.',
            'nama_produk.unique'       => 'Nama produk roti sudah terdaftar.',
            'porsi_per_batch.min'      => 'Hasil per batch minimal 1 unit.',
            'id_bahan.required'        => 'Minimal harus ada 1 bahan baku dalam resep.',
            'id_bahan.*.distinct'      => 'Tidak boleh memilih bahan baku yang sama lebih dari satu kali dalam satu resep.',
            'jumlah_takaran.*.gt'      => 'Jumlah takaran bahan harus lebih dari 0.',
        ]);

        DB::transaction(function () use ($request) {
            $produk = ProdukModel::create([
                'kode_produk'       => strtoupper($request->kode_produk),
                'nama_produk'       => $request->nama_produk,
                'porsi_per_batch'   => $request->porsi_per_batch,
                'biaya_operasional' => $request->biaya_operasional ?? 0,
                'harga_jual'        => $request->harga_jual ?? 0,
                'keterangan'        => $request->keterangan,
            ]);

            foreach ($request->id_bahan as $index => $idBahan) {
                ResepProdukModel::create([
                    'id_produk'      => $produk->id_produk,
                    'id_bahan'       => $idBahan,
                    'jumlah_takaran' => $request->jumlah_takaran[$index],
                ]);
            }
        });

        return redirect()->route('produk.index')->with('success', 'Master Produk dan komposisi resep berhasil disimpan.');
    }

    public function show(ProdukModel $produk)
    {
        $produk->load(['reseps.bahan.satuan', 'reseps.bahan.stokBahans']);
        return view('produk.show', compact('produk'));
    }

    public function edit(ProdukModel $produk)
    {
        $produk->load('reseps.bahan.satuan');
        $bahans = BahanModel::with('satuan')->orderBy('nama_bahan', 'asc')->get();

        return view('produk.edit', compact('produk', 'bahans'));
    }

    public function update(Request $request, ProdukModel $produk)
    {
        $request->validate([
            'kode_produk'        => ['required', 'string', 'max:30', Rule::unique('produks', 'kode_produk')->ignore($produk->id_produk, 'id_produk')],
            'nama_produk'        => ['required', 'string', 'max:100', Rule::unique('produks', 'nama_produk')->ignore($produk->id_produk, 'id_produk')],
            'porsi_per_batch'    => 'required|integer|min:1',
            'biaya_operasional'  => 'nullable|numeric|min:0',
            'harga_jual'         => 'nullable|numeric|min:0',
            'keterangan'         => 'nullable|string',
            'id_bahan'           => 'required|array|min:1',
            'id_bahan.*'         => 'required|exists:bahans,id_bahan|distinct',
            'jumlah_takaran'     => 'required|array|min:1',
            'jumlah_takaran.*'   => 'required|numeric|gt:0',
        ], [
            'id_bahan.*.distinct' => 'Tidak boleh memilih bahan baku yang sama lebih dari satu kali dalam satu resep.',
            'jumlah_takaran.*.gt' => 'Jumlah takaran bahan harus lebih dari 0.',
        ]);

        DB::transaction(function () use ($request, $produk) {
            $produk->update([
                'kode_produk'       => strtoupper($request->kode_produk),
                'nama_produk'       => $request->nama_produk,
                'porsi_per_batch'   => $request->porsi_per_batch,
                'biaya_operasional' => $request->biaya_operasional ?? 0,
                'harga_jual'        => $request->harga_jual ?? 0,
                'keterangan'        => $request->keterangan,
            ]);

            $produk->reseps()->delete();

            foreach ($request->id_bahan as $index => $idBahan) {
                ResepProdukModel::create([
                    'id_produk'      => $produk->id_produk,
                    'id_bahan'       => $idBahan,
                    'jumlah_takaran' => $request->jumlah_takaran[$index],
                ]);
            }
        });

        return redirect()->route('produk.index')->with('success', 'Master Produk dan resep berhasil diperbarui.');
    }

    public function destroy(ProdukModel $produk)
    {
        if ($produk->produksis()->exists()) {
            return redirect()->route('produk.index')->with('error', 'Produk terkunci dan tidak dapat dihapus karena sudah memiliki riwayat transaksi produksi.');
        }

        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Master Produk berhasil dihapus.');
    }
}