<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'nama_perusahaan',
        'nama_kontak',
        'nomor_telepon',
        'email',
        'alamat',
    ];
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }
    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'produk_supplier');
    }
}
