<?php

namespace App\Http\Controllers;

use App\Models\BahanModel;
use App\Models\SatuanModel;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        $bahans = BahanModel::with('satuan')
            ->withCount('stokBahans')
            ->orderBy('nama_bahan', 'asc')
            ->get();

        return view('bahan.index', compact('bahans'));
    }

    public function create(Request $request)
    {
        $satuans = SatuanModel::orderBy('nama_satuan', 'asc')->get();
        $redirectTo = $request->query('redirect_to');
        return view('bahan.create', compact('satuans', 'redirectTo'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'nama_bahan' => ucwords(strtolower(trim($request->nama_bahan ?? ''))),
        ]);

        $request->validate([
            'nama_bahan'           => 'required|string|max:100|unique:bahans,nama_bahan',
            'id_satuan'            => 'required|exists:satuans,id_satuan',
            'masa_kadaluarsa_hari' => 'required|integer|min:1|max:3650',
        ], [
            'nama_bahan.required'           => 'Nama bahan wajib diisi.',
            'nama_bahan.unique'             => 'Nama bahan ini sudah terdaftar.',
            'id_satuan.required'            => 'Satuan beli wajib dipilih.',
            'id_satuan.exists'              => 'Satuan yang dipilih tidak valid.',
            'masa_kadaluarsa_hari.required' => 'Batas kadaluarsa normatif wajib diisi.',
            'masa_kadaluarsa_hari.min'      => 'Batas kadaluarsa minimal 1 hari.',
        ]);

        $bahan = BahanModel::create([
            'nama_bahan'           => $request->nama_bahan,
            'id_satuan'            => $request->id_satuan,
            'masa_kadaluarsa_hari' => $request->masa_kadaluarsa_hari,
        ]);

        $bahan->load('satuan');

        // Jika disimpan lewat Modal AJAX (tanpa reload halaman)
        if ($request->ajax() || $request->wantsJson()) {
            $singkatan = $bahan->satuan->singkatan ?? '-';
            return response()->json([
                'success' => true,
                'message' => 'Bahan "' . $bahan->nama_bahan . '" berhasil ditambahkan.',
                'data'    => [
                    'id'              => $bahan->id_bahan,
                    'label'           => $bahan->nama_bahan . ' (' . $singkatan . ')',
                    'satuan'          => $bahan->satuan->singkatan ?? $bahan->satuan->nama_satuan ?? 'Unit',
                    'masa_kadaluarsa' => $bahan->masa_kadaluarsa_hari,
                ],
            ]);
        }

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Bahan "' . $bahan->nama_bahan . '" berhasil ditambahkan.')
                ->with('selected_bahan', $bahan->id_bahan);
        }

        return redirect()->route('bahan.index')->with('success', 'Master bahan berhasil ditambahkan.');
    }

    public function edit(BahanModel $bahan)
    {
        if ($bahan->stokBahans()->exists()) {
            return redirect()->route('bahan.index')
                ->with('error', 'Bahan tidak dapat diubah karena sudah memiliki riwayat stok/transaksi.');
        }

        $satuans = SatuanModel::orderBy('nama_satuan', 'asc')->get();
        return view('bahan.edit', compact('bahan', 'satuans'));
    }

    public function update(Request $request, BahanModel $bahan)
    {
        if ($bahan->stokBahans()->exists()) {
            return redirect()->route('bahan.index')
                ->with('error', 'Bahan tidak dapat diubah karena sudah memiliki riwayat stok/transaksi.');
        }

        $request->merge([
            'nama_bahan' => ucwords(strtolower(trim($request->nama_bahan ?? ''))),
        ]);

        $request->validate([
            'nama_bahan'           => 'required|string|max:100|unique:bahans,nama_bahan,' . $bahan->id_bahan . ',id_bahan',
            'id_satuan'            => 'required|exists:satuans,id_satuan',
            'masa_kadaluarsa_hari' => 'required|integer|min:1|max:3650',
        ], [
            'nama_bahan.required'           => 'Nama bahan wajib diisi.',
            'nama_bahan.unique'             => 'Nama bahan sudah digunakan.',
            'id_satuan.required'            => 'Satuan beli wajib dipilih.',
            'masa_kadaluarsa_hari.required' => 'Batas kadaluarsa normatif wajib diisi.',
            'masa_kadaluarsa_hari.min'      => 'Batas kadaluarsa minimal 1 hari.',
        ]);

        $bahan->update([
            'nama_bahan'           => $request->nama_bahan,
            'id_satuan'            => $request->id_satuan,
            'masa_kadaluarsa_hari' => $request->masa_kadaluarsa_hari,
        ]);

        return redirect()->route('bahan.index')->with('success', 'Master bahan berhasil diperbarui.');
    }

    public function destroy(BahanModel $bahan)
    {
        if ($bahan->stokBahans()->exists()) {
            return redirect()->route('bahan.index')
                ->with('error', 'Bahan tidak dapat dihapus karena sudah memiliki riwayat stok/transaksi.');
        }

        $bahan->delete();
        return redirect()->route('bahan.index')->with('success', 'Master bahan berhasil dihapus.');
    }
}