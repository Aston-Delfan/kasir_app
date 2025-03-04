<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelians';

    protected $fillable = [
        'tanggal_pembelian',
        'total_harga',
        'supplier_id',
    ];

    // Relasi ke Supplier (header pembelian)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke DetailPembelian (baris-baris detail pembelian)
    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}
