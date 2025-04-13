<?php

namespace App\Filament\Widgets;

use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Produk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class POSOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Hitung total penjualan hari ini
        $penjualanHariIni = Penjualan::whereDate('tanggal_penjualan', today())
            ->sum('total_harga');

        // Hitung total penjualan bulan ini
        $penjualanBulanIni = Penjualan::whereMonth('tanggal_penjualan', now()->month)
            ->whereYear('tanggal_penjualan', now()->year)
            ->sum('total_harga');

        // Hitung total pembelian hari ini
        $pembelianHariIni = Pembelian::whereDate('tanggal_pembelian', today())
            ->sum('total_harga');

        // Hitung total pembelian bulan ini
        $pembelianBulanIni = Pembelian::whereMonth('tanggal_pembelian', now()->month)
            ->whereYear('tanggal_pembelian', now()->year)
            ->sum('total_harga');

        // Hitung total produk dengan stok kosong
        $stokKosong = Produk::where('stok', 0)->count();

        // Hitung total produk dengan stok kurang dari 10
        $stokMenipis = Produk::where('stok', '>', 0)
            ->where('stok', '<', 10)
            ->count();

        // Hitung total produk
        $totalProduk = Produk::count();

        return [
            Stat::make('Penjualan Hari Ini', 'Rp ' . number_format($penjualanHariIni, 0, ',', '.'))
                ->description('Total transaksi penjualan hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Penjualan Bulan Ini', 'Rp ' . number_format($penjualanBulanIni, 0, ',', '.'))
                ->description('Total transaksi penjualan bulan ini')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),

            Stat::make('Pembelian Hari Ini', 'Rp ' . number_format($pembelianHariIni, 0, ',', '.'))
                ->description('Total transaksi pembelian hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),

            Stat::make('Stok Barang', $totalProduk)
                ->description($stokKosong . ' stok habis, ' . $stokMenipis . ' stok menipis')
                ->descriptionIcon('heroicon-m-tag')
                ->color($stokKosong > 0 ? 'danger' : 'success'),
        ];
    }
}
