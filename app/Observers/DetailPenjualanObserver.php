<?php

namespace App\Observers;

use App\Models\DetailPenjualan;
use App\Models\Produk;

class DetailPenjualanObserver
{
    /**
     * Handle the DetailPenjualan "created" event.
     */
    public function creating(DetailPenjualan $detailPenjualan): bool
    {
        $produk = Produk::find($detailPenjualan->produk_id);
        if ($produk && $detailPenjualan->jumlah_produk > $produk->stok) {
            return false; // Prevent creation if stock is insufficient
        }
        return true;
    }

    public function created(DetailPenjualan $detailPenjualan): void
    {
        // Kurangi stok produk saat penjualan dibuat
        $produk = Produk::find($detailPenjualan->produk_id);
        if ($produk) {
            // Add safety check here as well
            if ($produk->stok >= $detailPenjualan->jumlah_produk) {
                $produk->stok -= $detailPenjualan->jumlah_produk;
                $produk->save();
            }
        }

        // Update total harga di tabel penjualan
        $this->updatePenjualanTotal($detailPenjualan->penjualan_id);
    }

    /**
     * Handle the DetailPenjualan "updated" event.
     */
    public function updated(DetailPenjualan $detailPenjualan): void
    {
        // Jika jumlah produk berubah, update stok
        if ($detailPenjualan->isDirty('jumlah_produk')) {
            $produk = Produk::find($detailPenjualan->produk_id);
            $selisih = $detailPenjualan->getOriginal('jumlah_produk') - $detailPenjualan->jumlah_produk;
            $produk->stok += $selisih;
            $produk->save();
        }

        // Update total harga di tabel penjualan
        $this->updatePenjualanTotal($detailPenjualan->penjualan_id);
    }

    /**
     * Handle the DetailPenjualan "deleted" event.
     */
    public function deleted(DetailPenjualan $detailPenjualan): void
    {
        // Kembalikan stok produk saat detail penjualan dihapus
        $produk = Produk::find($detailPenjualan->produk_id);
        if ($produk) {
            $produk->stok += $detailPenjualan->jumlah_produk;
            $produk->save();
        }

        // Update total harga di tabel penjualan
        $this->updatePenjualanTotal($detailPenjualan->penjualan_id);
    }

    /**
     * Update total penjualan
     */
    private function updatePenjualanTotal($penjualanId): void
    {
        $total = DetailPenjualan::where('penjualan_id', $penjualanId)->sum('subtotal');
        $penjualan = \App\Models\Penjualan::find($penjualanId);
        if ($penjualan) {
            $penjualan->total_harga = $total;
            $penjualan->save();
        }
    }
}
