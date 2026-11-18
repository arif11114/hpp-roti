<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SatuanModel extends Model
{
    use HasUuids;

    protected $table = 'satuans';
    protected $primaryKey = 'id_satuan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nama_satuan', 'singkatan'];

    public function bahans()
    {
        return $this->hasMany(BahanModel::class, 'id_satuan', 'id_satuan');
    }
}