<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptsRouteKey;

class StokBahanModel extends Model
{
    use HasUuids, EncryptsRouteKey;

    protected $table = 'stok_bahans';
    protected $primaryKey = 'id_stok';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_bahan',
        'id_supplier',
        'tanggal_transaksi',
        'tanggal_produksi',
        'tanggal_expired',
        'jumlah',
        'sisa_stok',
        'harga_beli',
        'bukti_nota',
        'batas_notif_expired',
    ];

    public function bahan()
    {
        return $this->belongsTo(BahanModel::class, 'id_bahan', 'id_bahan');
    }

    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'id_supplier', 'id_supplier');
    }

    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksiModel::class, 'id_stok', 'id_stok');
    }

    public function getHargaPerUnitAttribute(): float
    {
        return $this->jumlah > 0 ? ($this->harga_beli / $this->jumlah) : 0;
    }
}