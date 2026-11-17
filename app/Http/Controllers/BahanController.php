<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Satuan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        // Ambil data bahan beserta relasi satuannya, dan hitung apakah sudah terpakai di stok_bahans
        $bahans = Bahan::with('satuan')
            ->withCount('stokBahans')
            ->orderBy('nama_bahan', 'asc')
            ->get();

        return view('bahan.index', compact('bahans'));
    }

    public function create()
    {
        $satuans = Satuan::orderBy('nama_satuan', 'asc')->get();
        return view('bahan.create', compact('satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100|unique:bahans,nama_bahan',
            'id_satuan'  => 'required|exists:satuans,id_satuan',
        ], [
            'nama_bahan.required' => 'Nama bahan wajib diisi.',
            'nama_bahan.unique'   => 'Nama bahan ini sudah terdaftar.',
            'id_satuan.required'  => 'Satuan beli wajib dipilih.',
            'id_satuan.exists'    => 'Satuan yang dipilih tidak valid.',
        ]);

        Bahan::create([
            'nama_bahan' => $request->nama_bahan,
            'id_satuan'  => $request->id_satuan,
        ]);

        return redirect()->route('bahan.index')->with('success', 'Master bahan berhasil ditambahkan.');
    }

    public function edit(Bahan $bahan)
    {
        $satuans = Satuan::orderBy('nama_satuan', 'asc')->get();
        return view('bahan.edit', compact('bahan', 'satuans'));
    }

    public function update(Request $request, Bahan $bahan)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100|unique:bahans,nama_bahan,' . $bahan->id_bahan . ',id_bahan',
            'id_satuan'  => 'required|exists:satuans,id_satuan',
        ], [
            'nama_bahan.required' => 'Nama bahan wajib diisi.',
            'nama_bahan.unique'   => 'Nama bahan sudah digunakan.',
            'id_satuan.required'  => 'Satuan beli wajib dipilih.',
        ]);

        $bahan->update([
            'nama_bahan' => $request->nama_bahan,
            'id_satuan'  => $request->id_satuan,
        ]);

        return redirect()->route('bahan.index')->with('success', 'Master bahan berhasil diperbarui.');
    }

    public function destroy(Bahan $bahan)
    {
        // Sesuai aturan Pak Saiful: bahan yang sudah masuk transaksi stok TIDAK BOLEH dihapus
        if ($bahan->stokBahans()->exists()) {
            return redirect()->route('bahan.index')->with('error', 'Bahan tidak dapat dihapus karena sudah memiliki riwayat stok/transaksi.');
        }

        $bahan->delete();
        return redirect()->route('bahan.index')->with('success', 'Master bahan berhasil dihapus.');
    }
}