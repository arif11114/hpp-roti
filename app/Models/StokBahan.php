<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    use HasUuids;

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
        'harga_beli',
        'batas_notif_expired',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'id_bahan', 'id_bahan');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }
}