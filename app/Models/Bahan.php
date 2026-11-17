<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasUuids;

    protected $table = 'bahans';
    protected $primaryKey = 'id_bahan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_satuan', 'nama_bahan'];

    // Relasi: Bahan milik satu satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan', 'id_satuan');
    }

    // Relasi: Bahan bisa memiliki banyak riwayat stok (untuk cek kunci hapus)
    public function stokBahans()
    {
        return $this->hasMany(StokBahan::class, 'id_bahan', 'id_bahan');
    }
}