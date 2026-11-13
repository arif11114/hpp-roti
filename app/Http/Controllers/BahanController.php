<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    // 1. Menampilkan seluruh daftar bahan (Read/View)
    public function index()
    {
        $bahans = Bahan::orderBy('id_bahan', 'asc')->get();
        return view('bahan.index', compact('bahans'));
    }

    // 2. Menampilkan halaman form tambah bahan baru
    public function create()
    {
        return view('bahan.create');
    }

    // 3. Menyimpan data bahan baru ke database (Insert)
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100|unique:bahans,nama_bahan',
        ], [
            'nama_bahan.required' => 'Nama bahan wajib diisi.',
            'nama_bahan.unique'   => 'Nama bahan ini sudah terdaftar.',
        ]);

        Bahan::create([
            'nama_bahan' => $request->nama_bahan,
        ]);

        return redirect()->route('bahan.index')
                         ->with('success', 'Data bahan berhasil ditambahkan!');
    }

    // 4. Menampilkan halaman form edit bahan
    public function edit(Bahan $bahan)
    {
        return view('bahan.edit', compact('bahan'));
    }

    // 5. Menyimpan perubahan data bahan ke database (Update)
    public function update(Request $request, Bahan $bahan)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100|unique:bahans,nama_bahan,' . $bahan->id_bahan . ',id_bahan',
        ], [
            'nama_bahan.required' => 'Nama bahan wajib diisi.',
            'nama_bahan.unique'   => 'Nama bahan ini sudah digunakan bahan lain.',
        ]);

        $bahan->update([
            'nama_bahan' => $request->nama_bahan,
        ]);

        return redirect()->route('bahan.index')
                         ->with('success', 'Data bahan berhasil diperbarui!');
    }

    // 6. Menghapus data bahan (Delete)
    public function destroy(Bahan $bahan)
    {
        $bahan->delete();

        return redirect()->route('bahan.index')
                         ->with('success', 'Data bahan berhasil dihapus!');
    }
}