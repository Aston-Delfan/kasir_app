<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualans';

    protected $fillable = [
        'tanggal_penjualan',
        'pelanggan_id',
    ];

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi ke DetailPenjualan
    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
