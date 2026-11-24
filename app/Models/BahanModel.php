<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Traits\EncryptsRouteKey;

class BahanModel extends Model
{
    use HasUuids, EncryptsRouteKey;

    protected $table = 'bahans';
    protected $primaryKey = 'id_bahan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_satuan', 'nama_bahan'];

    public function satuan()
    {
        return $this->belongsTo(SatuanModel::class, 'id_satuan', 'id_satuan');
    }

    public function stokBahans()
    {
        return $this->hasMany(StokBahanModel::class, 'id_bahan', 'id_bahan');
    }
    public function resepProduks()
    {
        return $this->hasMany(ResepProdukModel::class, 'id_bahan', 'id_bahan');
    }

    public function getTotalStokTersediaAttribute(): float
    {
        return (float) $this->stokBahans()->sum('sisa_stok');
    }
}