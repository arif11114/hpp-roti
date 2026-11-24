<?php

namespace App\Models;

use App\Traits\EncryptsRouteKey;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProduksiModel extends Model
{
    use HasUuids, EncryptsRouteKey;

    protected $table = 'produksis';
    protected $primaryKey = 'id_produksi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_produksi',
        'id_produk',
        'user_id',
        'tanggal_produksi',
        'jumlah_batch',
        'total_unit_hasil',
        'total_biaya_bahan',
        'total_biaya_operasional',
        'total_hpp',
        'hpp_per_unit',
        'catatan',
    ];

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'id_produk', 'id_produk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailProduksiModel::class, 'id_produksi', 'id_produksi');
    }
}