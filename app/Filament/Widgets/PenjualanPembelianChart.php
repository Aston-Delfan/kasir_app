<?php

namespace App\Filament\Widgets;

use App\Models\Pembelian;
use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PenjualanPembelianChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Penjualan & Pembelian';
    protected static ?string $pollingInterval = '15s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $days = collect(range(6, 0))
            ->map(function ($day) {
                return now()->subDays($day)->format('Y-m-d');
            });

        // Data penjualan per hari
        $penjualanData = $days->mapWithKeys(function ($date) {
            return [
                Carbon::parse($date)->format('d/m') => Penjualan::whereDate('tanggal_penjualan', $date)
                    ->sum('total_harga')
            ];
        });

        // Data pembelian per hari
        $pembelianData = $days->mapWithKeys(function ($date) {
            return [
                Carbon::parse($date)->format('d/m') => Pembelian::whereDate('tanggal_pembelian', $date)
                    ->sum('total_harga')
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan',
                    'data' => $penjualanData->values()->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Pembelian',
                    'data' => $pembelianData->values()->toArray(),
                    'backgroundColor' => 'rgba(234, 179, 8, 0.5)',
                    'borderColor' => 'rgb(234, 179, 8)',
                ]
            ],
            'labels' => $penjualanData->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
