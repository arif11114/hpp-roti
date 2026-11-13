<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    // Memberi tahu Laravel nama tabel & primary key custom kita
    protected $table = 'bahans';
    protected $primaryKey = 'id_bahan';

    // Kolom yang diizinkan untuk diisi lewat form input
    protected $fillable = [
        'nama_bahan',
    ];
}