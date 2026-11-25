<?php

namespace App\Http\Controllers;

use App\Models\StokBahanModel;
use App\Models\BahanModel;
use App\Models\SupplierModel;
use App\Models\SatuanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StokBahanController extends Controller
{
    public function index()
    {
        $stokBahans = StokBahanModel::with(['bahan.satuan', 'supplier'])
            ->latest('tanggal_transaksi')
            ->get();

        return view('stok_bahan.index', compact('stokBahans'));
    }

    public function create()
    {
        $bahans = BahanModel::with('satuan')->orderBy('nama_bahan', 'asc')->get();
        $suppliers = SupplierModel::orderBy('nama_supplier', 'asc')->get();
        $satuans = SatuanModel::orderBy('nama_satuan', 'asc')->get();

        return view('stok_bahan.create', compact('bahans', 'suppliers', 'satuans'));
    }

    public function store(Request $request)
    {
        $minExpiredDate = $request->filled('tanggal_transaksi')
            ? Carbon::parse($request->tanggal_transaksi)->addDays(2)->format('Y-m-d')
            : now()->addDays(2)->format('Y-m-d');

        $request->validate([
            'id_bahan'            => 'required|exists:bahans,id_bahan',
            'id_supplier'         => 'required|exists:suppliers,id_supplier',
            'tanggal_transaksi'   => 'required|date|before_or_equal:today',
            'tanggal_produksi'    => 'nullable|date|before_or_equal:today|before_or_equal:tanggal_transaksi',
            'tanggal_expired'     => 'nullable|date|after_or_equal:' . $minExpiredDate,
            'jumlah'              => 'required|numeric|min:0.01',
            'harga_beli'          => 'required|integer|min:0',
            'batas_notif_expired' => 'required|integer|min:1',
            'bukti_nota'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'id_bahan.required'                 => 'Nama bahan wajib dipilih.',
            'id_supplier.required'              => 'Supplier wajib dipilih.',
            'tanggal_transaksi.required'        => 'Tanggal beli wajib diisi.',
            'tanggal_transaksi.before_or_equal' => 'Tanggal beli tidak boleh melebihi hari ini.',
            'tanggal_produksi.before_or_equal'  => 'Tanggal produksi tidak boleh melebihi tanggal beli maupun hari ini.',
            'tanggal_expired.after_or_equal'    => 'Tanggal expired minimal harus H+2 dari tanggal beli (tidak boleh mepet hari ini/besok).',
            'jumlah.required'                   => 'Jumlah stok wajib diisi.',
            'harga_beli.required'               => 'Harga beli wajib diisi.',
            'bukti_nota.mimes'                  => 'Bukti nota harus berupa file JPG, JPEG, PNG, atau PDF.',
            'bukti_nota.max'                    => 'Ukuran file bukti nota maksimal 2 MB.',
        ]);

        $data = $request->except('bukti_nota');
        $data['sisa_stok'] = $request->jumlah;

        if ($request->hasFile('bukti_nota')) {
            $data['bukti_nota'] = $request->file('bukti_nota')->store('nota', 'public');
        }

        $stok = StokBahanModel::create($data);
        $bahan = BahanModel::find($request->id_bahan);

        $redirect = redirect()->route('stok-bahan.index')
            ->with('success', 'Data stok bahan berhasil ditambahkan!');

        // Cek peringatan jika tanggal expired lebih cepat dari masa kadaluarsa normatif bahan
        if ($request->filled('tanggal_expired') && $bahan && $bahan->masa_kadaluarsa_hari) {
            $tglBeli = Carbon::parse($request->tanggal_transaksi)->startOfDay();
            $tglExp = Carbon::parse($request->tanggal_expired)->startOfDay();
            $selisihHari = $tglBeli->diffInDays($tglExp, false);

            if ($selisihHari < $bahan->masa_kadaluarsa_hari) {
                $redirect->with('warning', "Peringatan Kadaluarsa Mepet: Bahan \"{$bahan->nama_bahan}\" memiliki masa simpan normatif {$bahan->masa_kadaluarsa_hari} hari, tetapi stok yang baru dibeli akan expired dalam {$selisihHari} hari (" . $tglExp->format('d-m-Y') . "). Harap segera diprioritaskan untuk produksi!");
            }
        }

        return $redirect;
    }

    public function edit(StokBahanModel $stokBahan)
    {
        $bahans = BahanModel::with('satuan')->orderBy('nama_bahan', 'asc')->get();
        $suppliers = SupplierModel::orderBy('nama_supplier', 'asc')->get();
        $satuans = SatuanModel::orderBy('nama_satuan', 'asc')->get();

        return view('stok_bahan.edit', compact('stokBahan', 'bahans', 'suppliers', 'satuans'));
    }

    public function update(Request $request, StokBahanModel $stokBahan)
    {
        $minExpiredDate = $request->filled('tanggal_transaksi')
            ? Carbon::parse($request->tanggal_transaksi)->addDays(2)->format('Y-m-d')
            : now()->addDays(2)->format('Y-m-d');

        $request->validate([
            'id_bahan'            => 'required|exists:bahans,id_bahan',
            'id_supplier'         => 'required|exists:suppliers,id_supplier',
            'tanggal_transaksi'   => 'required|date|before_or_equal:today',
            'tanggal_produksi'    => 'nullable|date|before_or_equal:today|before_or_equal:tanggal_transaksi',
            'tanggal_expired'     => 'nullable|date|after_or_equal:' . $minExpiredDate,
            'jumlah'              => 'required|numeric|min:0.01',
            'harga_beli'          => 'required|integer|min:0',
            'batas_notif_expired' => 'required|integer|min:1',
            'bukti_nota'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'id_bahan.required'                 => 'Nama bahan wajib dipilih.',
            'id_supplier.required'              => 'Supplier wajib dipilih.',
            'tanggal_transaksi.required'        => 'Tanggal beli wajib diisi.',
            'tanggal_transaksi.before_or_equal' => 'Tanggal beli tidak boleh melebihi hari ini.',
            'tanggal_produksi.before_or_equal'  => 'Tanggal produksi tidak boleh melebihi tanggal beli maupun hari ini.',
            'tanggal_expired.after_or_equal'    => 'Tanggal expired minimal harus H+2 dari tanggal beli (tidak boleh mepet hari ini/besok).',
            'jumlah.required'                   => 'Jumlah stok wajib diisi.',
            'harga_beli.required'               => 'Harga beli wajib diisi.',
            'bukti_nota.mimes'                  => 'Bukti nota harus berupa file JPG, JPEG, PNG, atau PDF.',
            'bukti_nota.max'                    => 'Ukuran file bukti nota maksimal 2 MB.',
        ]);

        $data = $request->except('bukti_nota');

        if ($request->hasFile('bukti_nota')) {
            if ($stokBahan->bukti_nota && Storage::disk('public')->exists($stokBahan->bukti_nota)) {
                Storage::disk('public')->delete($stokBahan->bukti_nota);
            }
            $data['bukti_nota'] = $request->file('bukti_nota')->store('nota', 'public');
        }

        $stokBahan->update($data);
        $bahan = BahanModel::find($request->id_bahan);

        $redirect = redirect()->route('stok-bahan.index')
            ->with('success', 'Data stok bahan berhasil diperbarui!');

        if ($request->filled('tanggal_expired') && $bahan && $bahan->masa_kadaluarsa_hari) {
            $tglBeli = Carbon::parse($request->tanggal_transaksi)->startOfDay();
            $tglExp = Carbon::parse($request->tanggal_expired)->startOfDay();
            $selisihHari = $tglBeli->diffInDays($tglExp, false);

            if ($selisihHari < $bahan->masa_kadaluarsa_hari) {
                $redirect->with('warning', "Peringatan Kadaluarsa Mepet: Bahan \"{$bahan->nama_bahan}\" memiliki masa simpan normatif {$bahan->masa_kadaluarsa_hari} hari, tetapi stok ini akan expired dalam {$selisihHari} hari (" . $tglExp->format('d-m-Y') . ").");
            }
        }

        return $redirect;
    }

    public function destroy(StokBahanModel $stokBahan)
    {
        if ($stokBahan->bukti_nota && Storage::disk('public')->exists($stokBahan->bukti_nota)) {
            Storage::disk('public')->delete($stokBahan->bukti_nota);
        }

        $stokBahan->delete();

        return redirect()->route('stok-bahan.index')->with('success', 'Data stok bahan berhasil dihapus!');
    }
}