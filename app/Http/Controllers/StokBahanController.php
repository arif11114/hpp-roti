<?php

namespace App\Http\Controllers;

use App\Models\StokBahan;
use App\Models\Bahan;
use Illuminate\Http\Request;

class StokBahanController extends Controller
{
    public function index()
    {
        $stokBahans = StokBahan::with('bahan')->latest('tanggal_belanja')->get();
        return view('stok_bahan.index', compact('stokBahans'));
    }

    public function create()
    {
        $bahans = Bahan::orderBy('nama_bahan', 'asc')->get();
        return view('stok_bahan.create', compact('bahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bahan' => 'required|exists:bahans,id_bahan',
            'tanggal_belanja' => 'required|date',
            'jumlah_gram' => 'required|numeric|min:0.01',
            'harga_beli' => 'required|integer|min:0',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_belanja',
            'batas_notif_expired' => 'required|integer|min:1',
        ]);

        StokBahan::create($request->all());

        return redirect()->route('stok-bahan.index')->with('success', 'Data stok bahan berhasil ditambahkan!');
    }

    public function edit(StokBahan $stokBahan)
    {
        $bahans = Bahan::orderBy('nama_bahan', 'asc')->get();
        return view('stok_bahan.edit', compact('stokBahan', 'bahans'));
    }

    public function update(Request $request, StokBahan $stokBahan)
    {
        $request->validate([
            'id_bahan' => 'required|exists:bahans,id_bahan',
            'tanggal_belanja' => 'required|date',
            'jumlah_gram' => 'required|numeric|min:0.01',
            'harga_beli' => 'required|integer|min:0',
            'tanggal_expired' => 'required|date|after_or_equal:tanggal_belanja',
            'batas_notif_expired' => 'required|integer|min:1',
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