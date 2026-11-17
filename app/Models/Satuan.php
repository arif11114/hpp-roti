<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasUuids;

    protected $table = 'satuans';
    protected $primaryKey = 'id_satuan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nama_satuan', 'singkatan'];

    // Relasi: Satu satuan bisa dimiliki banyak bahan
    public function bahans()
    {
        return $this->hasMany(Bahan::class, 'id_satuan', 'id_satuan');
    }
}