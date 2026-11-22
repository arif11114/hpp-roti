<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = SupplierModel::withCount('stokBahans')
            ->orderBy('nama_supplier', 'asc')
            ->get();

        return view('supplier.index', compact('suppliers'));
    }

    public function create(Request $request)
    {
        $redirectTo = $request->query('redirect_to');
        return view('supplier.create', compact('redirectTo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100|unique:suppliers,nama_supplier',
            'kontak'        => 'nullable|string|max:30',
            'alamat'        => 'nullable|string',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.unique'   => 'Nama supplier ini sudah terdaftar.',
            'kontak.max'             => 'Kontak maksimal 30 karakter.',
        ]);

        $supplier = SupplierModel::create([
            'nama_supplier' => $request->nama_supplier,
            'kontak'        => $request->kontak,
            'alamat'        => $request->alamat,
        ]);

        // Jika disimpan lewat Modal AJAX (tanpa reload halaman)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier "' . $supplier->nama_supplier . '" berhasil ditambahkan.',
                'data'    => [
                    'id'    => $supplier->id_supplier,
                    'label' => $supplier->nama_supplier,
                ],
            ]);
        }

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Supplier "' . $supplier->nama_supplier . '" berhasil ditambahkan.')
                ->with('selected_supplier', $supplier->id_supplier);
        }

        return redirect()->route('supplier.index')->with('success', 'Master supplier berhasil ditambahkan.');
    }

    public function edit(SupplierModel $supplier)
    {
        if ($supplier->stokBahans()->exists()) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak dapat diubah karena sudah memiliki riwayat transaksi stok.');
        }

        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, SupplierModel $supplier)
    {
        if ($supplier->stokBahans()->exists()) {
            return redirect()->route('supplier.index')
                ->with('error', 'Supplier tidak dapat diubah karena sudah memiliki riwayat transaksi stok.');
        }

        $request->validate([
            'nama_supplier' => 'required|string|max:100|unique:suppliers,nama_supplier,' . $supplier->id_supplier . ',id_supplier',
            'kontak'        => 'nullable|string|max:30',
            'alamat'        => 'nullable|string',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'nama_supplier.unique'   => 'Nama supplier sudah digunakan.',
            'kontak.max'             => 'Kontak maksimal 30 karakter.',
        ]);

        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'kontak'        => $request->kontak,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Master supplier berhasil diperbarui.');
    }

    public function destroy(SupplierModel $supplier)
    {
        if ($supplier->stokBahans()->exists()) {
            return redirect()->route('supplier.index')->with('error', 'Supplier tidak dapat dihapus karena sudah memiliki riwayat transaksi stok.');
        }

        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Master supplier berhasil dihapus.');
    }
}