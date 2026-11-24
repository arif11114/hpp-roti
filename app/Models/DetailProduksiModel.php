<?php

namespace App\Models;

use App\Traits\EncryptsRouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DetailProduksiModel extends Model
{
    use HasUuids, EncryptsRouteKey;

    protected $table = 'detail_produksis';
    protected $primaryKey = 'id_detail_produksi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produksi',
        'id_bahan',
        'id_stok',
        'jumlah_terpakai',
        'harga_satuan_batch',
        'subtotal_biaya',
    ];

    public function produksi()
    {
        return $this->belongsTo(ProduksiModel::class, 'id_produksi', 'id_produksi');
    }

    public function bahan()
    {
        return $this->belongsTo(BahanModel::class, 'id_bahan', 'id_bahan');
    }

    public function stokBahan()
    {
        return $this->belongsTo(StokBahanModel::class, 'id_stok', 'id_stok');
    }
}