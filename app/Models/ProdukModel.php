<?php

namespace App\Models;

use App\Traits\EncryptsRouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProdukModel extends Model
{
    use HasUuids, EncryptsRouteKey;

    protected $table = 'produks';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'porsi_per_batch',
        'biaya_operasional',
        'harga_jual',
        'keterangan',
    ];

    public function reseps()
    {
        return $this->hasMany(ResepProdukModel::class, 'id_produk', 'id_produk');
    }

    public function produksis()
    {
        return $this->hasMany(ProduksiModel::class, 'id_produk', 'id_produk');
    }
}