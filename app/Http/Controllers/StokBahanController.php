<?php

namespace App\Http\Controllers;

use App\Models\StokBahan;
use App\Models\Bahan;
use App\Models\Supplier;
use Illuminate\Http\Request;

class StokBahanController extends Controller
{
    public function index()
    {
        $stokBahans = StokBahan::with(['bahan.satuan', 'supplier'])
            ->latest('tanggal_transaksi')
            ->get();

        return view('stok_bahan.index', compact('stokBahans'));
    }

    public function create()
    {
        $bahans = Bahan::with('satuan')->orderBy('nama_bahan', 'asc')->get();
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();

        return view('stok_bahan.create', compact('bahans', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bahan'            => 'required|exists:bahans,id_bahan',
            'id_supplier'         => 'required|exists:suppliers,id_supplier',
            'tanggal_transaksi'   => 'required|date',
            'tanggal_produksi'    => 'nullable|date|before_or_equal:tanggal_transaksi',
            'tanggal_expired'     => 'nullable|date|after_or_equal:tanggal_transaksi',
            'jumlah'              => 'required|numeric|min:0.01',
            'harga_beli'          => 'required|integer|min:0',
            'batas_notif_expired' => 'required|integer|min:1',
        ], [
            'id_bahan.required'                => 'Nama bahan wajib dipilih.',
            'id_supplier.required'             => 'Supplier wajib dipilih.',
            'tanggal_transaksi.required'       => 'Tanggal beli wajib diisi.',
            'tanggal_produksi.before_or_equal' => 'Tanggal produksi tidak boleh melebihi tanggal beli.',
            'tanggal_expired.after_or_equal'   => 'Tanggal expired tidak boleh sebelum tanggal beli.',
            'jumlah.required'                  => 'Jumlah stok wajib diisi.',
            'harga_beli.required'              => 'Harga beli wajib diisi.',
        ]);

        StokBahan::create($request->all());

        return redirect()->route('stok-bahan.index')->with('success', 'Data stok bahan berhasil ditambahkan!');
    }

    public function edit(StokBahan $stokBahan)
    {
        $bahans = Bahan::with('satuan')->orderBy('nama_bahan', 'asc')->get();
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();

        return view('stok_bahan.edit', compact('stokBahan', 'bahans', 'suppliers'));
    }

    public function update(Request $request, StokBahan $stokBahan)
    {
        $request->validate([
            'id_bahan'            => 'required|exists:bahans,id_bahan',
            'id_supplier'         => 'required|exists:suppliers,id_supplier',
            'tanggal_transaksi'   => 'required|date',
            'tanggal_produksi'    => 'nullable|date|before_or_equal:tanggal_transaksi',
            'tanggal_expired'     => 'nullable|date|after_or_equal:tanggal_transaksi',
            'jumlah'              => 'required|numeric|min:0.01',
            'harga_beli'          => 'required|integer|min:0',
            'batas_notif_expired' => 'required|integer|min:1',
        ], [
            'id_bahan.required'                => 'Nama bahan wajib dipilih.',
            'id_supplier.required'             => 'Supplier wajib dipilih.',
            'tanggal_transaksi.required'       => 'Tanggal beli wajib diisi.',
            'tanggal_produksi.before_or_equal' => 'Tanggal produksi tidak boleh melebihi tanggal beli.',
            'tanggal_expired.after_or_equal'   => 'Tanggal expired tidak boleh sebelum tanggal beli.',
            'jumlah.required'                  => 'Jumlah stok wajib diisi.',
            'harga_beli.required'              => 'Harga beli wajib diisi.',
        ]);

        $stokBahan->update($request->all());

        return redirect()->route('stok-bahan.index')->with('success', 'Data stok bahan berhasil diperbarui!');
    }

    public function destroy(StokBahan $stokBahan)
    {
        $stokBahan->delete();

        return redirect()->route('stok-bahan.index')->with('success', 'Data stok bahan berhasil dihapus!');
    }
}