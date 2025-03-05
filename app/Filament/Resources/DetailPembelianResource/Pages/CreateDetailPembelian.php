<?php

namespace App\Filament\Resources\DetailPembelianResource\Pages;

use App\Filament\Resources\DetailPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailPembelian extends CreateRecord
{
    protected static string $resource = DetailPembelianResource::class;
    // public static function afterSave($record, array $data): void
    // {
    //     // Ambil ID pembelian dari salah satu record detail (asumsi semua detail milik pembelian yang sama)
    //     $pembelianId = $record->pembelian_id;

    //     // Hitung total harga berdasarkan data repeater yang disubmit (jika tersedia)
    //     $total = collect($data['detail'] ?? [])
    //         ->sum(fn ($item) => $item['subtotal'] ?? 0);

    //     // Update record pembelian
    //     \App\Models\Pembelian::find($pembelianId)->update(['total_harga' => $total]);
    // }
}
