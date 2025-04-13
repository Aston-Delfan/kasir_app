<?php

namespace App\Filament\Resources\DetailPenjualanResource\Pages;

use Filament\Actions;
use App\Models\Produk;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DetailPenjualanResource;
use App\Filament\Resources\DetailPenjualanResource\Widgets\DetailPenjualanWidget;

class CreateDetailPenjualan extends CreateRecord
{
    protected static string $resource = DetailPenjualanResource::class;
    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Simpan')
                ->submit('create')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
{
    // Check stock availability before creating
    $produk = Produk::find($data['produk_id']);
    if ($produk && $data['jumlah_produk'] > $produk->stok) {
        Notification::make()
            ->danger()
            ->title('Stok Tidak Cukup')
            ->body("Stok {$produk->nama_produk} hanya tersedia {$produk->stok} item.")
            ->send();

        $this->halt();
    }

    return $data;
}

    protected function getRedirectUrl():string
    {
        $id = $this->record->penjualan_id;
        return route(
            'filament.admin.resources.detail-penjualans.create',['penjualan_id' => $id]
        );
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getFooterWidgets(): array
    {
        return [
            DetailPenjualanWidget::make([
                'record' => request('penjualan_id'),
         ]),
        ];
    }
}
