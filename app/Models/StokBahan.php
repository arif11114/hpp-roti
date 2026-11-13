<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    use HasFactory;

    protected $table = 'stok_bahans';
    protected $primaryKey = 'id_stok';

    protected $fillable = [
        'id_bahan',
        'tanggal_belanja',
        'jumlah_gram',
        'harga_beli',
        'tanggal_expired',
        'batas_notif_expired',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'id_bahan', 'id_bahan');
    }
}