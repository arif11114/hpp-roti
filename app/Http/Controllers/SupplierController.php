<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('stokBahans')
            ->orderBy('nama_supplier', 'asc')
            ->get();

        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('supplier.create');
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

        Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'kontak'        => $request->kontak,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Master supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
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

    public function destroy(Supplier $supplier)
    {
        if ($supplier->stokBahans()->exists()) {
            return redirect()->route('supplier.index')->with('error', 'Supplier tidak dapat dihapus karena sudah memiliki riwayat transaksi stok.');
        }

        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Master supplier berhasil dihapus.');
    }
}