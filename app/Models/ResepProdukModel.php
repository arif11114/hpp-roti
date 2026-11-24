<?php

namespace App\Models;

use App\Traits\EncryptsRouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ResepProdukModel extends Model
{
    use HasUuids, EncryptsRouteKey;

    protected $table = 'resep_produks';
    protected $primaryKey = 'id_resep';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produk',
        'id_bahan',
        'jumlah_takaran',
    ];

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'id_produk', 'id_produk');
    }

    public function bahan()
    {
        return $this->belongsTo(BahanModel::class, 'id_bahan', 'id_bahan');
    }
}