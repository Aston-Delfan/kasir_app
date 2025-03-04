<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelians';

    protected $fillable = [
        'jumlah_produk',
        'subtotal',
        'pembelian_id',
        'produk_id',
    ];

    // Relasi ke Pembelian (header pembelian)
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    // Relasi ke Produk (produk yang dibeli)
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
