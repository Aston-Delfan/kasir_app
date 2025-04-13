<?php

namespace App\Observers;

use App\Models\DetailPembelian;
use App\Models\Produk;

class DetailPembelianObserver
{
    /**
     * Handle the DetailPembelian "created" event.
     */
    public function created(DetailPembelian $detailPembelian): void
    {
        // Tambah stok produk saat pembelian dibuat
        $produk = Produk::find($detailPembelian->produk_id);
        if ($produk) {
            $produk->stok += $detailPembelian->jumlah_produk;
            $produk->save();
        }

        // Update total harga di tabel pembelian
        $this->updatePembelianTotal($detailPembelian->pembelian_id);
    }

    /**
     * Handle the DetailPembelian "updated" event.
     */
    public function updated(DetailPembelian $detailPembelian): void
    {
        // Jika jumlah produk berubah, update stok
        if ($detailPembelian->isDirty('jumlah_produk')) {
            $produk = Produk::find($detailPembelian->produk_id);
            if ($produk) {
                // Hitung selisih: nilai baru - nilai lama
                $selisih = $detailPembelian->jumlah_produk - $detailPembelian->getOriginal('jumlah_produk');
                $produk->stok += $selisih;
                $produk->save();
            }
        }

        // Update total harga di tabel pembelian
        $this->updatePembelianTotal($detailPembelian->pembelian_id);
    }

    /**
     * Handle the DetailPembelian "deleted" event.
     */
    public function deleted(DetailPembelian $detailPembelian): void
    {
        // Kurangi stok produk saat detail pembelian dihapus
        $produk = Produk::find($detailPembelian->produk_id);
        if ($produk) {
            $produk->stok -= $detailPembelian->jumlah_produk;
            $produk->save();
        }

        // Update total harga di tabel pembelian
        $this->updatePembelianTotal($detailPembelian->pembelian_id);
    }

    /**
     * Update total pembelian
     */
    private function updatePembelianTotal($pembelianId): void
    {
        $total = DetailPembelian::where('pembelian_id', $pembelianId)
            ->join('produks', 'detail_pembelians.produk_id', '=', 'produks.id')
            ->selectRaw('SUM(detail_pembelians.jumlah_produk * produks.harga) as total')
            ->first()->total ?? 0;

        $pembelian = \App\Models\Pembelian::find($pembelianId);
        if ($pembelian) {
            $pembelian->total_harga = $total;
            $pembelian->save();
        }
    }
}
