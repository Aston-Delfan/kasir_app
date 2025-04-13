<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Picqer\Barcode\BarcodeGeneratorPNG;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'kode',
        'nama_produk',
        'category_id',
        'kategori',
        'stok',
        'harga',
        'barcode',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            // Generate barcode jika belum ada
            if (!$produk->barcode) {
                $produk->barcode = static::generateUniqueBarcode();
            }
        });
    }

        // Method untuk generate unique barcode
        public static function generateUniqueBarcode()
        {
            $prefix = 'PRD';
            $timestamp = now()->format('YmdHis');
            $random = Str::random(4);
            $barcode = $prefix . $timestamp . $random;

            // Validasi barcode belum pernah digunakan
            $attempts = 0;
            $maxAttempts = 10;

            while (static::where('barcode', $barcode)->exists() && $attempts < $maxAttempts) {
                $random = Str::random(4);
                $barcode = $prefix . $timestamp . $random;
                $attempts++;
            }

            if ($attempts >= $maxAttempts) {
                // Fallback to ensure uniqueness
                $barcode = $prefix . $timestamp . Str::random(8);
            }

            return $barcode;
        }

        // Method untuk mendapatkan barcode HTML
        public function getBarcodeHtml()
        {
            if (!$this->barcode) {
                return null;
            }

            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($this->barcode, $generator::TYPE_CODE_128));

            return '<img src="data:image/png;base64,' . $barcode . '" alt="' . $this->barcode . '">';
        }

        // Method untuk mendapatkan barcode data URI
        public function getBarcodeDataUri()
        {
            if (!$this->barcode) {
                return null;
            }

            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($this->barcode, $generator::TYPE_CODE_128));

            return 'data:image/png;base64,' . $barcode;
        }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'produk_supplier');
    }
    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelian::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
