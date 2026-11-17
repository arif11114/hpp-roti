<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        // withCount('bahans') menghitung berapa bahan yang memakai satuan ini
        $satuans = Satuan::withCount('bahans')->orderBy('nama_satuan', 'asc')->get();
        return view('satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan',
            'singkatan'   => 'required|string|max:15',
        ], [
            'nama_satuan.required' => 'Nama satuan wajib diisi.',
            'nama_satuan.unique'   => 'Satuan ini sudah ada di daftar.',
            'singkatan.required'   => 'Singkatan satuan wajib diisi (contoh: kg, L, bks).',
        ]);

        Satuan::create([
            'nama_satuan' => $request->nama_satuan,
            'singkatan'   => $request->singkatan,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Data satuan berhasil ditambahkan.');
    }

    public function edit(Satuan $satuan)
    {
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan,' . $satuan->id_satuan . ',id_satuan',
            'singkatan'   => 'required|string|max:15',
        ], [
            'nama_satuan.required' => 'Nama satuan wajib diisi.',
            'nama_satuan.unique'   => 'Nama satuan sudah digunakan.',
            'singkatan.required'   => 'Singkatan satuan wajib diisi.',
        ]);

        $satuan->update([
            'nama_satuan' => $request->nama_satuan,
            'singkatan'   => $request->singkatan,
        ]);

        return redirect()->route('satuan.index')->with('success', 'Data satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        // Kunci penghapusan jika satuan sudah dipakai oleh Master Bahan
        if ($satuan->bahans()->exists()) {
            return redirect()->route('satuan.index')->with('error', 'Satuan tidak dapat dihapus karena sedang digunakan pada Master Bahan.');
        }

        $satuan->delete();
        return redirect()->route('satuan.index')->with('success', 'Data satuan berhasil dihapus.');
    }
}