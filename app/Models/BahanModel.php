<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BahanModel extends Model
{
    use HasUuids;

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
}