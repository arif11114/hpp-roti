<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasUuids;

    protected $table = 'suppliers';
    protected $primaryKey = 'id_supplier';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nama_supplier', 'kontak', 'alamat'];

    public function stokBahans()
    {
        return $this->hasMany(StokBahanModel::class, 'id_supplier', 'id_supplier');
    }
}