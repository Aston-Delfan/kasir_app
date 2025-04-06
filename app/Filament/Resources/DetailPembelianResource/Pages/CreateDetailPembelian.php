<?php

namespace App\Filament\Resources\DetailPembelianResource\Pages;

use App\Filament\Resources\DetailPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use App\Filament\Resources\DetailPembelianResource\Widgets\DetailPembelianWidget;


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
    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Simpan')
                ->submit('create')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function getRedirectUrl():string
    {
        $id = $this->record->pembelian_id;
        return route(
            'filament.admin.resources.detail-pembelians.create',['pembelian_id' => $id]
        );
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getFooterWidgets(): array
    {
        return [
            DetailPembelianWidget::make([
                'record' => request('pembelian_id'),
         ]),
        ];
    }
}
