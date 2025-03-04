<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'kode',
        'nama_produk',
        'kategori',
        'stok',
        'harga',
    ];

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'produk_supplier');
    }
    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}
