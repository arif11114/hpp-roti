<?php

namespace App\Http\Controllers;

use App\Models\SatuanModel;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = SatuanModel::withCount('bahans')->orderBy('nama_satuan', 'asc')->get();
        return view('satuan.index', compact('satuans'));
    }

    public function create(Request $request)
    {
        // Tangkap URL halaman asal jika diakses lewat tombol shortcut (...)
        $redirectTo = $request->query('redirect_to');
        return view('satuan.create', compact('redirectTo'));
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

        $satuan = SatuanModel::create([
            'nama_satuan' => $request->nama_satuan,
            'singkatan'   => $request->singkatan,
        ]);

        // Jika berasal dari tombol shortcut (...), kembalikan langsung ke form asal!
        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Satuan "' . $satuan->nama_satuan . '" berhasil ditambahkan.')
                ->with('selected_satuan', $satuan->id_satuan);
        }

        return redirect()->route('satuan.index')->with('success', 'Data satuan berhasil ditambahkan.');
    }

    public function edit(SatuanModel $satuan)
    {
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, SatuanModel $satuan)
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

    public function destroy(SatuanModel $satuan)
    {
        if ($satuan->bahans()->exists()) {
            return redirect()->route('satuan.index')->with('error', 'Satuan tidak dapat dihapus karena sedang digunakan pada Master Bahan.');
        }

        $satuan->delete();
        return redirect()->route('satuan.index')->with('success', 'Data satuan berhasil dihapus.');
    }
}